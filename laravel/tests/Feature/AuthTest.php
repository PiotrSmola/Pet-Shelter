<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in(): void
    {
        $user = User::factory()->create(['password' => 'Password1!']);

        $this->post(route('login.authenticate'), ['email' => $user->email, 'password' => 'Password1!', 'remember' => 'on'])
            ->assertRedirect(route('user.profile'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_does_not_apply_password_complexity_rules(): void
    {
        $user = User::factory()->create(['password' => 'simple']);

        $this->post(route('login.authenticate'), ['email' => $user->email, 'password' => 'simple'])
            ->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.authenticate'), ['email' => $user->email, 'password' => 'Wrong1!pass'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_throttled(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.authenticate'), ['email' => $user->email, 'password' => 'Wrong1!pass'])
                ->assertRedirect();
        }

        $this->post(route('login.authenticate'), ['email' => $user->email, 'password' => 'Password1!'])
            ->assertStatus(429);
        $this->assertGuest();
    }

    public function test_logout_requires_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/logout')->assertStatus(405);
        $this->assertAuthenticated();

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('index'));
        $this->assertGuest();
    }

    public function test_register_requires_a_strong_password(): void
    {
        $data = [
            'name' => 'Anna',
            'last_name' => 'Nowak',
            'email' => 'anna@example.com',
            'phone_number' => '123456789',
            'password' => 'weakpass',
            'password_confirmation' => 'weakpass',
        ];

        $this->post(route('register.store'), $data)->assertSessionHasErrors('password');
        $this->assertGuest();

        $data['password'] = $data['password_confirmation'] = 'Strong1!pass';

        $this->post(route('register.store'), $data)->assertRedirect(route('index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'anna@example.com', 'role' => 'customer']);
    }
}
