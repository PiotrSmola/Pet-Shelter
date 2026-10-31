<?php

namespace Tests\Feature;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_available_pets_are_listed(): void
    {
        $available = Pet::factory()->create();
        $cancelled = Pet::factory()->create();
        Adoption::factory()->for($cancelled)->status('cancelled')->create();

        foreach (['pending', 'reserved', 'completed'] as $status) {
            Adoption::factory()->status($status)->create();
        }

        $response = $this->get(route('pets.index'));

        $response->assertOk()->assertViewIs('pets.index');
        $this->assertEqualsCanonicalizing(
            [$available->id, $cancelled->id],
            $response->viewData('pets')->pluck('id')->all()
        );
    }

    public function test_filters_by_species_case_insensitively_weight_and_age(): void
    {
        $match = Pet::factory()->create(['species' => 'Dog', 'weight' => 10, 'age' => 4]);
        Pet::factory()->create(['species' => 'Cat', 'weight' => 10, 'age' => 4]);
        Pet::factory()->create(['species' => 'Dog', 'weight' => 20, 'age' => 4]);
        Pet::factory()->create(['species' => 'Dog', 'weight' => 10, 'age' => 9]);

        $response = $this->get('/pets?species=DOG&weight=medium&age_min=2&age_max=5');

        $response->assertOk();
        $this->assertSame([$match->id], $response->viewData('pets')->pluck('id')->all());
        $this->assertSame(
            ['species' => 'dog', 'weight' => 'medium', 'age_min' => 2, 'age_max' => 5, 'sort' => null],
            $response->viewData('filters')
        );
    }

    public function test_invalid_filters_are_ignored(): void
    {
        Pet::factory()->count(2)->create();

        $response = $this->get('/pets?species=bird&weight=huge&age_min=abc&age_max[]=1&sort=name');

        $response->assertOk();
        $this->assertCount(2, $response->viewData('pets'));
        $this->assertSame(
            ['species' => null, 'weight' => null, 'age_min' => null, 'age_max' => null, 'sort' => null],
            $response->viewData('filters')
        );
    }

    public function test_sorts_by_age_and_paginates_by_nine_keeping_the_query_string(): void
    {
        Pet::factory()->count(10)->sequence(fn ($sequence) => ['age' => $sequence->index])->create();

        $response = $this->get('/pets?sort=age_desc');

        $pets = $response->viewData('pets');
        $this->assertCount(9, $pets);
        $this->assertSame(9, $pets->first()->age);
        $this->assertStringContainsString('sort=age_desc', $pets->nextPageUrl());
    }

    public function test_old_species_urls_redirect_to_the_filtered_list(): void
    {
        $this->get('/pets/dogs')->assertRedirect('/pets?species=dog');
        $this->get('/pets/cats')->assertRedirect('/pets?species=cat');
    }

    public function test_pet_page_shows_availability(): void
    {
        $pet = Pet::factory()->create();
        Adoption::factory()->for($pet)->status('completed')->create();

        $response = $this->get(route('pets.show', $pet));

        $response->assertOk()->assertViewIs('pets.show')->assertViewHas('isAvailable', false);
    }
}
