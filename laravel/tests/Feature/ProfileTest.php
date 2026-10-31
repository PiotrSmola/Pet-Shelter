<?php

namespace Tests\Feature;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_lists_own_adoptions(): void
    {
        $adoption = Adoption::factory()->create();
        Adoption::factory()->create();

        $response = $this->actingAs($adoption->user)->get(route('user.profile'));

        $response->assertOk()->assertViewIs('user.profile');
        $this->assertSame([$adoption->id], $response->viewData('adoptions')->pluck('id')->all());
    }

    public function test_edit_page_renders(): void
    {
        $this->actingAs(User::factory()->create())->get(route('user.edit'))
            ->assertOk()
            ->assertViewIs('user.edit');
    }

    public function test_user_updates_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('user.update'), [
            'name' => 'Anna',
            'last_name' => 'Nowak',
            'email' => $user->email,
            'phone_number' => '111222333',
        ])->assertRedirect(route('user.profile'))->assertSessionHas('success');

        $this->assertSame('111222333', $user->fresh()->phone_number);
    }

    public function test_email_must_be_unique(): void
    {
        $other = User::factory()->create();

        $this->actingAs(User::factory()->create())->put(route('user.update'), [
            'name' => 'Anna',
            'last_name' => 'Nowak',
            'email' => $other->email,
            'phone_number' => '111222333',
        ])->assertSessionHasErrors('email');
    }

    public function test_user_changes_password(): void
    {
        $user = User::factory()->create(['password' => 'Password1!']);

        $this->actingAs($user)->put(route('user.change_password'), [
            'current_password' => 'wrong',
            'new_password' => 'NewPass1!',
            'new_password_confirmation' => 'NewPass1!',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($user)->put(route('user.change_password'), [
            'current_password' => 'Password1!',
            'new_password' => 'NewPass1!',
            'new_password_confirmation' => 'NewPass1!',
        ])->assertRedirect(route('user.profile'));

        $this->assertTrue(Hash::check('NewPass1!', $user->fresh()->password));
    }
}
