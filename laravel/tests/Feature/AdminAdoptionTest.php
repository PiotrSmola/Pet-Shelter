<?php

namespace Tests\Feature;

use App\Models\Adoption;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAdoptionTest extends TestCase
{
    use RefreshDatabase;

    private function updateStatus(Adoption $adoption, mixed $status)
    {
        return $this->actingAs(User::factory()->admin()->create())
            ->postJson(route('admin.adoptions.updateStatus', $adoption), ['status' => $status]);
    }

    public function test_admin_completes_a_pending_request(): void
    {
        $adoption = Adoption::factory()->create(['adoption_date' => '2024-01-01']);

        $this->updateStatus($adoption, 'completed')
            ->assertOk()
            ->assertExactJson(['success' => true, 'status' => 'completed', 'label' => 'Adopted']);

        $adoption->refresh();
        $this->assertSame('completed', $adoption->status);
        $this->assertTrue($adoption->adoption_date->isToday());
    }

    public function test_admin_cancels_a_reservation(): void
    {
        $adoption = Adoption::factory()->status('reserved')->create();

        $this->updateStatus($adoption, 'cancelled')->assertOk()->assertJson(['label' => 'Cancelled']);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $adoption = Adoption::factory()->create();

        foreach (['adopted', 'pending', 'reserved', '', null] as $status) {
            $this->updateStatus($adoption, $status)->assertStatus(422)->assertJsonStructure(['message']);
        }

        $this->assertSame('pending', $adoption->fresh()->status);
    }

    public function test_closed_adoption_cannot_be_changed(): void
    {
        $completed = Adoption::factory()->status('completed')->create();
        $cancelled = Adoption::factory()->status('cancelled')->create();

        $this->updateStatus($completed, 'cancelled')->assertStatus(422)->assertJsonStructure(['message']);
        $this->updateStatus($cancelled, 'completed')->assertStatus(422);

        $this->assertSame('completed', $completed->fresh()->status);
        $this->assertSame('cancelled', $cancelled->fresh()->status);
    }

    public function test_chart_covers_the_last_12_months(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 15));
        $pet = Pet::factory()->create();
        Adoption::factory()->for($pet)->status('completed')->create(['adoption_date' => '2026-10-01']);
        Adoption::factory()->status('completed')->create(['adoption_date' => '2025-11-30']);
        Adoption::factory()->status('completed')->create(['adoption_date' => '2025-10-31']);
        Adoption::factory()->status('cancelled')->create(['adoption_date' => '2026-10-02']);

        $response = $this->actingAs(User::factory()->admin()->create())->get(route('admin.adoptions'));

        $chart = $response->assertOk()->viewData('chart');
        $this->assertCount(12, $chart['labels']);
        $this->assertSame('Nov 2025', $chart['labels'][0]);
        $this->assertSame('Oct 2026', $chart['labels'][11]);
        $this->assertSame([1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1], $chart['data']);
    }

    public function test_list_can_be_filtered_by_status(): void
    {
        Adoption::factory()->create();
        $reserved = Adoption::factory()->status('reserved')->create();

        $response = $this->actingAs(User::factory()->admin()->create())->get('/admin/adoptions?status=reserved');

        $response->assertOk()->assertViewHas('status', 'reserved');
        $this->assertSame([$reserved->id], $response->viewData('adoptions')->pluck('id')->all());
    }

    public function test_admin_routes_are_forbidden_for_customers(): void
    {
        $adoption = Adoption::factory()->create();
        $this->actingAs($adoption->user);

        foreach (['/admin', '/admin/adoptions', '/admin/users', '/admin/pets', '/admin/pets/create', '/admin/vaccinations'] as $url) {
            $this->get($url)->assertForbidden();
        }

        $this->postJson(route('admin.adoptions.updateStatus', $adoption), ['status' => 'completed'])->assertForbidden();
        $this->delete(route('admin.pets.destroy', $adoption->pet))->assertForbidden();
        $this->assertSame('pending', $adoption->fresh()->status);
    }

    public function test_admin_routes_redirect_guests_to_login(): void
    {
        $this->get('/admin/adoptions')->assertRedirect(route('login'));
    }
}
