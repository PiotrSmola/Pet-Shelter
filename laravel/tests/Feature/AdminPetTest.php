<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPetTest extends TestCase
{
    use RefreshDatabase;

    private function petData(array $overrides = []): array
    {
        return [
            'name' => 'Rex',
            'species' => 'Dog',
            'breed' => 'Labrador',
            'age' => 3,
            'weight' => 25.5,
            'description' => 'Good boy.',
            ...$overrides,
        ];
    }

    public function test_store_saves_photo_on_public_disk_with_a_random_name(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.pets.store'), $this->petData(['photo' => UploadedFile::fake()->create('rex.jpg', 50, 'image/jpeg')]))
            ->assertRedirect(route('admin.pets'))
            ->assertSessionHas('success');

        $pet = Pet::sole();
        $this->assertStringStartsWith('photos/', $pet->photo_path);
        $this->assertNotSame('photos/rex.jpg', $pet->photo_path);
        Storage::disk('public')->assertExists($pet->photo_path);
        $this->assertStringEndsWith('/storage/' . $pet->photo_path, $pet->photo_url);
    }

    public function test_update_replaces_photo_and_deletes_the_old_one(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/old.jpg', 'old');
        $pet = Pet::factory()->create(['photo_path' => 'photos/old.jpg']);

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.pets.update', $pet), $this->petData(['photo' => UploadedFile::fake()->create('new.jpg', 50, 'image/jpeg')]))
            ->assertRedirect(route('admin.pets'));

        $pet->refresh();
        $this->assertSame('Rex', $pet->name);
        $this->assertNotSame('photos/old.jpg', $pet->photo_path);
        Storage::disk('public')->assertExists($pet->photo_path);
        Storage::disk('public')->assertMissing('photos/old.jpg');
    }

    public function test_update_without_photo_keeps_the_current_one(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/old.jpg', 'old');
        $pet = Pet::factory()->create(['photo_path' => 'photos/old.jpg']);

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.pets.update', $pet), $this->petData())
            ->assertRedirect(route('admin.pets'));

        $this->assertSame('photos/old.jpg', $pet->fresh()->photo_path);
        Storage::disk('public')->assertExists('photos/old.jpg');
    }

    public function test_destroy_deletes_pet_and_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/old.jpg', 'old');
        $pet = Pet::factory()->create(['photo_path' => 'photos/old.jpg']);

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.pets.destroy', $pet))
            ->assertRedirect(route('admin.pets'));

        $this->assertModelMissing($pet);
        Storage::disk('public')->assertMissing('photos/old.jpg');
    }

    public function test_invalid_pet_data_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.pets.store'), $this->petData(['species' => 'Bird', 'weight' => 0]))
            ->assertSessionHasErrors(['species', 'weight', 'photo']);

        $this->assertDatabaseCount('pets', 0);
    }
}
