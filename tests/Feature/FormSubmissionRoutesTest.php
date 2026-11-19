<?php

namespace Tests\Feature;

use App\Models\PostFoundItems;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FormSubmissionRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_claim_form_submits_a_request_for_a_found_item(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $foundItem = PostFoundItems::create([
            'item_name' => 'Blue notebook',
            'category' => 'Books',
            'condition' => 'Good',
            'found_location' => 'Main Library',
            'found_date' => now()->toDateString(),
            'description' => 'Blue notebook with a name written inside',
            'finder_name' => 'Finder',
            'phone' => '0712345678',
            'email' => null,
        ]);

        $this->actingAs($user)
            ->post(route('claim.store'), [
                'found_item_id' => $foundItem->id,
                'fullName' => 'Amina User',
                'studentNumber' => 'ST123',
                'contact' => 'amina@example.com',
                'proof' => UploadedFile::fake()->create('proof.pdf', 10, 'application/pdf'),
                'description' => 'My name is written on the inside cover.',
                'declaration' => '1',
            ])
            ->assertRedirect(route('claimItems'));

        $this->assertDatabaseHas('claim_items', [
            'user_id' => $user->id,
            'found_item_id' => $foundItem->id,
            'item_name' => 'Blue notebook',
            'status' => 'pending',
        ]);
    }

    public function test_profile_and_password_routes_update_the_authenticated_user(): void
    {
        $user = User::factory()->create([
            'password' => 'old-password',
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'firstName' => 'Amina',
                'lastName' => 'User',
                'email' => $user->email,
                'phone' => '0712345678',
            ])
            ->assertRedirect(route('profile'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Amina User',
            'telephone' => '0712345678',
        ]);

        $this->put(route('password.update'), [
            'currentPassword' => 'old-password',
            'newPassword' => 'new-password-123',
            'confirmPassword' => 'new-password-123',
        ])->assertRedirect(route('profile'));

        $this->assertTrue(password_verify('new-password-123', $user->fresh()->password));
    }

    public function test_all_form_pages_load_for_authenticated_users(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('postItem'))
            ->assertOk()
            ->assertSee('name="item_name"', false)
            ->assertSee('name="date_lost"', false)
            ->assertSee('name="item_photo"', false);

        foreach (['postItem', 'postFoundItems', 'claimItems', 'claimRequest', 'profile', 'searchItems', 'myItems', 'itemArchive'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_dashboard_quick_actions_link_to_laravel_routes(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk();

        $response->assertSee('href="' . route('postItem') . '"', false)
            ->assertSee('href="' . route('postFoundItems') . '"', false)
            ->assertSee('href="' . route('searchItems') . '"', false)
            ->assertSee('href="' . route('claimItems') . '"', false);
    }

    public function test_dashboard_shows_recent_lost_and_found_items_from_all_users(): void
    {
        $viewer = User::factory()->create();
        $reporter = User::factory()->create();

        \App\Models\PostItem::create([
            'category' => 'Electronics',
            'item_name' => 'Dashboard lost phone',
            'description' => 'Black phone with blue case',
            'location' => 'Main Library',
            'date_lost' => now()->toDateString(),
            'contact' => null,
            'photo_path' => 'lost-items/phone.jpg',
            'user_id' => $viewer->id,
        ]);

        \App\Models\PostFoundItems::create([
            'item_name' => 'Dashboard found wallet',
            'category' => 'Wallets',
            'condition' => 'Good',
            'found_location' => 'Cafeteria',
            'found_date' => now()->toDateString(),
            'description' => 'Brown wallet',
            'finder_name' => 'Finder',
            'phone' => '0712345678',
            'email' => null,
            'user_id' => $reporter->id,
        ]);

        $this->actingAs($viewer)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard lost phone')
            ->assertSee('Dashboard found wallet')
            ->assertSee('<h2 class="fw-bold mb-0">1</h2>', false)
            ->assertSee('Lost · Active')
            ->assertSee('Found · Active');
    }

    public function test_new_lost_report_appears_on_dashboard_after_submission(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('postItem.store'), [
                'category' => 'Electronics',
                'item_name' => 'New dashboard test phone',
                'description' => 'Black phone with a blue case',
                'location' => 'Main Library',
                'date_lost' => now()->toDateString(),
                'contact' => '',
                'item_photo' => UploadedFile::fake()->create('phone.jpg', 10, 'image/jpeg'),
            ])
            ->assertRedirect(route('postItem'));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('New dashboard test phone')
            ->assertSee('<h2 class="fw-bold mb-0">1</h2>', false);
    }

    public function test_search_items_shows_reports_from_all_users(): void
    {
        $viewer = User::factory()->create();
        $reporter = User::factory()->create();

        \App\Models\PostItem::create([
            'category' => 'Electronics',
            'item_name' => 'Shared lost phone',
            'description' => 'Black phone with blue case',
            'location' => 'Main Library',
            'date_lost' => now()->toDateString(),
            'contact' => null,
            'photo_path' => 'lost-items/phone.jpg',
            'user_id' => $reporter->id,
        ]);

        $this->actingAs($viewer)
            ->get(route('searchItems'))
            ->assertOk()
            ->assertSee('Shared lost phone')
            ->assertSee('Lost item');
    }

    public function test_guests_can_browse_active_reports_and_open_details_without_a_time_limit(): void
    {
        $reporter = User::factory()->create();
        $item = \App\Models\PostItem::create([
            'category' => 'Documents',
            'item_name' => 'Old lost student card',
            'description' => 'Student card misplaced and still missing.',
            'location' => 'Administration block',
            'date_lost' => '2024-01-10',
            'contact' => null,
            'user_id' => $reporter->id,
            'status' => 'active',
        ]);

        $this->get(route('searchItems'))
            ->assertOk()
            ->assertSee('Old lost student card')
            ->assertSee('Log in to report or claim an item');

        $this->get(route('itemDetails', ['type' => 'lost', 'id' => $item->id]))
            ->assertOk()
            ->assertSee('Old lost student card');
    }

    public function test_item_details_page_displays_the_saved_lost_item_photo(): void
    {
        $user = User::factory()->create();
        $item = \App\Models\PostItem::create([
            'category' => 'Electronics',
            'item_name' => 'Photo test phone',
            'description' => 'Black phone',
            'location' => 'Main Library',
            'date_lost' => now()->toDateString(),
            'contact' => null,
            'photo_path' => 'lost-items/photo-test-phone.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('itemDetails', ['type' => 'lost', 'id' => $item->id]))
            ->assertOk()
            ->assertSee('Photo test phone')
            ->assertSee('/storage/lost-items/photo-test-phone.jpg', false)
            ->assertSee('alt="Photo of Photo test phone"', false);
    }

    public function test_my_items_lists_only_the_current_users_lost_and_found_reports(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        \App\Models\PostItem::create([
            'category' => 'Electronics',
            'item_name' => 'Lost phone',
            'description' => 'Black phone',
            'location' => 'Library',
            'date_lost' => now()->toDateString(),
            'contact' => null,
            'photo_path' => 'lost-items/phone.jpg',
            'user_id' => $owner->id,
        ]);
        \App\Models\PostFoundItems::create([
            'item_name' => 'Found wallet',
            'category' => 'Wallets',
            'condition' => 'Good',
            'found_location' => 'Cafeteria',
            'found_date' => now()->toDateString(),
            'description' => 'Brown wallet',
            'finder_name' => 'Owner',
            'phone' => '0712345678',
            'email' => null,
            'user_id' => $owner->id,
        ]);
        \App\Models\PostItem::create([
            'category' => 'Books',
            'item_name' => 'Other user lost book',
            'description' => 'Blue book',
            'location' => 'Classroom',
            'date_lost' => now()->toDateString(),
            'contact' => null,
            'photo_path' => 'lost-items/book.jpg',
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($owner)
            ->get(route('myItems'))
            ->assertOk()
            ->assertSee('Lost phone')
            ->assertSee('Found wallet')
            ->assertDontSee('Other user lost book')
            ->assertSee('Lost Reports')
            ->assertSee('Found Reports');
    }
}
