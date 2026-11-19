<?php

namespace Tests\Feature;

use App\Models\PostFoundItems;
use App\Models\PostItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_archive_shows_inactive_lost_and_found_reports_and_live_summary_counts(): void
    {
        $user = User::factory()->create();
        $this->createLostItem($user, 'Resolved lost card', 'resolved');
        $this->createFoundItem($user, 'Claimed found wallet', 'claimed');
        $this->createLostItem($user, 'Still active keys', 'active');

        $this->actingAs($user)
            ->get(route('itemArchive'))
            ->assertOk()
            ->assertSee('Resolved lost card')
            ->assertSee('Claimed found wallet')
            ->assertDontSee('Still active keys')
            ->assertSee('>2</p>', false)
            ->assertSee('>1</p>', false)
            ->assertSee('Resolved or returned reports')
            ->assertSee('Closed, claimed or expired reports');
    }

    public function test_archive_search_filters_by_type_status_and_name(): void
    {
        $user = User::factory()->create();
        $this->createLostItem($user, 'Resolved lost card', 'resolved');
        $this->createLostItem($user, 'Closed lost bag', 'closed');
        $this->createFoundItem($user, 'Claimed found wallet', 'claimed');

        $this->actingAs($user)
            ->get(route('itemArchive', [
                'q' => 'wallet',
                'type' => 'found',
                'status' => 'claimed',
            ]))
            ->assertOk()
            ->assertSee('Claimed found wallet')
            ->assertDontSee('Resolved lost card')
            ->assertDontSee('Closed lost bag');
    }

    public function test_archive_remains_available_only_to_authenticated_users(): void
    {
        $this->get(route('itemArchive'))
            ->assertRedirect(route('login'));
    }

    public function test_owner_can_resolve_a_lost_item_and_it_then_appears_in_archive(): void
    {
        $owner = User::factory()->create();
        $item = $this->createLostItem($owner, 'Lost student card', 'active');

        $this->actingAs($owner)
            ->patch(route('items.status.update', ['type' => 'lost', 'id' => $item->id]), [
                'status' => 'resolved',
            ])
            ->assertRedirect(route('myItems'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('post_items', [
            'id' => $item->id,
            'status' => 'resolved',
        ]);

        $this->get(route('searchItems'))
            ->assertOk()
            ->assertDontSee('Lost student card');

        $this->get(route('itemArchive'))
            ->assertOk()
            ->assertSee('Lost student card');
    }

    public function test_only_the_item_owner_or_an_admin_can_change_its_status(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = $this->createLostItem($owner, 'Private owner report', 'active');

        $this->actingAs($otherUser)
            ->patch(route('items.status.update', ['type' => 'lost', 'id' => $item->id]), [
                'status' => 'resolved',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('post_items', [
            'id' => $item->id,
            'status' => 'active',
        ]);
    }

    private function createLostItem(User $user, string $name, string $status): PostItem
    {
        return PostItem::create([
            'category' => 'Documents',
            'item_name' => $name,
            'description' => $name . ' report description.',
            'location' => 'Library',
            'date_lost' => now()->toDateString(),
            'user_id' => $user->id,
            'status' => $status,
        ]);
    }

    private function createFoundItem(User $user, string $name, string $status): PostFoundItems
    {
        return PostFoundItems::create([
            'item_name' => $name,
            'category' => 'Wallets',
            'condition' => 'Good',
            'found_location' => 'Cafeteria',
            'found_date' => now()->toDateString(),
            'description' => $name . ' report description.',
            'finder_name' => 'Finder',
            'phone' => '0712345678',
            'email' => null,
            'user_id' => $user->id,
            'status' => $status,
        ]);
    }
}
