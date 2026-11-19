<?php

namespace Tests\Feature;

use App\Models\PostFoundItems;
use App\Models\PostItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemSimilarityTest extends TestCase
{
    use RefreshDatabase;

    public function test_found_report_displays_similar_lost_reports(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        PostItem::create([
            'category' => 'Bags',
            'item_name' => 'Black backpack',
            'description' => 'Black backpack with a red keychain',
            'location' => 'Main Library',
            'date_lost' => '2026-10-25',
            'contact' => null,
            'photo_path' => 'lost-items/backpack.jpg',
            'user_id' => $user->id,
        ]);

        $this->post(route('postFoundItems.store'), [
            'item_name' => 'Black backpack',
            'category' => 'Bags',
            'condition' => 'Good',
            'found_location' => 'Main Library',
            'found_date' => '2026-10-25',
            'description' => 'Black backpack with a red keychain',
            'finder_name' => 'Campus Staff',
            'phone' => '0712345678',
            'email' => null,
        ])
            ->assertRedirect(route('postFoundItems'))
            ->assertSessionHas('item_matches', fn (array $matches) => $matches[0]['score'] === 100);

        $this->assertDatabaseHas('post_found_items', [
            'item_name' => 'Black backpack',
            'category' => 'Bags',
        ]);
    }

    public function test_lost_report_displays_similar_found_reports(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        PostFoundItems::create([
            'item_name' => 'Black backpack',
            'category' => 'Bags',
            'condition' => 'Good',
            'found_location' => 'Main Library',
            'found_date' => '2026-10-25',
            'description' => 'Black backpack with a red keychain',
            'finder_name' => 'Campus Staff',
            'phone' => '0712345678',
            'email' => null,
        ]);

        $this->post(route('postItem.store'), [
            'category' => 'Bags',
            'item_name' => 'Black backpack',
            'description' => 'Black backpack with a red keychain',
            'location' => 'Main Library',
            'date_lost' => '2026-10-25',
            'contact' => '',
            'item_photo' => UploadedFile::fake()->create('backpack.jpg', 10, 'image/jpeg'),
        ])
            ->assertRedirect(route('postItem'))
            ->assertSessionHas('item_matches', fn (array $matches) => $matches[0]['score'] === 100);

        $this->assertDatabaseHas('post_items', [
            'item_name' => 'Black backpack',
            'category' => 'Bags',
        ]);
    }
}
