<?php

namespace Tests\Feature;

use App\Models\Adoption;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdoptionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_login(): void
    {
        $pet = Pet::factory()->create();

        $this->post(route('adopt', $pet))->assertRedirect(route('login'));
        $this->assertDatabaseCount('adoptions', 0);
    }

    public function test_adopt_creates_a_pending_request(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        $this->actingAs($user)->post(route('adopt', $pet))
            ->assertRedirect(route('user.profile'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('adoptions', [
            'pet_id' => $pet->id,
            'customer_id' => $user->id,
            'status' => 'pending',
            'expires_at' => null,
        ]);
        $this->assertFalse($pet->isAvailable());
    }

    public function test_a_pet_cannot_be_requested_twice(): void
    {
        $pet = Pet::factory()->create();
        $first = User::factory()->create();
        $second = User::factory()->create();

        $this->actingAs($first)->post(route('adopt', $pet));
        $this->actingAs($first)->post(route('adopt', $pet))->assertSessionHas('error');
        $this->actingAs($second)->post(route('reserve', $pet))->assertSessionHas('error');

        $this->assertDatabaseCount('adoptions', 1);
    }

    public function test_admin_cannot_adopt(): void
    {
        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();

        $this->actingAs($admin)->post(route('adopt', $pet))->assertForbidden();
        $this->actingAs($admin)->post(route('reserve', $pet))->assertForbidden();

        $this->assertDatabaseCount('adoptions', 0);
    }

    public function test_reserve_sets_a_48_hour_expiry(): void
    {
        $this->freezeTime();
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        $this->actingAs($user)->post(route('reserve', $pet))->assertRedirect(route('user.profile'));

        $adoption = Adoption::sole();
        $this->assertSame('reserved', $adoption->status);
        $this->assertSame(now()->addHours(48)->toDateTimeString(), $adoption->expires_at->toDateTimeString());
    }

    public function test_expiry_command_cancels_only_expired_reservations(): void
    {
        $expired = Adoption::factory()->status('reserved')->create(['expires_at' => now()->subMinute()]);
        $valid = Adoption::factory()->status('reserved')->create();
        $pending = Adoption::factory()->create();

        $this->artisan('adoptions:expire-reservations')->assertSuccessful();

        $this->assertSame('cancelled', $expired->fresh()->status);
        $this->assertSame('reserved', $valid->fresh()->status);
        $this->assertSame('pending', $pending->fresh()->status);
        $this->assertTrue($expired->pet->isAvailable());
    }

    public function test_expired_reservation_is_released_on_the_next_request(): void
    {
        $adoption = Adoption::factory()->status('reserved')->create(['expires_at' => now()->subMinute()]);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('adopt', $adoption->pet))->assertRedirect(route('user.profile'));

        $this->assertSame('cancelled', $adoption->fresh()->status);
        $this->assertDatabaseHas('adoptions', ['customer_id' => $user->id, 'status' => 'pending']);
    }

    public function test_user_can_cancel_own_active_request(): void
    {
        $adoption = Adoption::factory()->status('reserved')->create();

        $this->actingAs($adoption->user)->from('/user/profile')
            ->post(route('adoptions.cancel', $adoption))
            ->assertRedirect('/user/profile')
            ->assertSessionHas('success');

        $this->assertSame('cancelled', $adoption->fresh()->status);
    }

    public function test_user_cannot_cancel_someone_elses_request(): void
    {
        $adoption = Adoption::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('adoptions.cancel', $adoption))
            ->assertForbidden();

        $this->assertSame('pending', $adoption->fresh()->status);
    }

    public function test_completed_adoption_cannot_be_cancelled_by_the_customer(): void
    {
        $adoption = Adoption::factory()->status('completed')->create();

        $this->actingAs($adoption->user)->post(route('adoptions.cancel', $adoption))->assertSessionHas('error');

        $this->assertSame('completed', $adoption->fresh()->status);
    }
}
