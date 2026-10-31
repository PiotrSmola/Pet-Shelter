<?php

namespace Tests\Feature;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private function userData(array $overrides = []): array
    {
        return ['name' => 'Anna', 'last_name' => 'Nowak', 'email' => 'anna@example.com', 'phone_number' => '123456789', ...$overrides];
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHas('error');

        $this->assertModelExists($admin);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('success');

        $this->assertModelMissing($user);
    }

    public function test_admin_cannot_remove_own_admin_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put(route('admin.users.update', $admin), $this->userData())->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->isAdmin());
    }

    public function test_user_with_adoptions_cannot_become_admin(): void
    {
        $adoption = Adoption::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.users.update', $adoption->user), $this->userData(['admin' => 'on']))
            ->assertSessionHas('error');

        $this->assertFalse($adoption->user->fresh()->isAdmin());
    }

    public function test_admin_updates_a_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.users.update', $user), $this->userData(['admin' => 'on']))
            ->assertRedirect(route('admin.users'));

        $user->refresh();
        $this->assertSame('anna@example.com', $user->email);
        $this->assertTrue($user->isAdmin());
    }
}
