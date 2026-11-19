<?php

namespace Tests\Feature;

use App\Models\PostItem;
use App\Models\PostFoundItems;
use App\Models\ClaimItems;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_reports_created_by_other_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reporter = User::factory()->create();

        PostItem::create([
            'category' => 'Electronics',
            'item_name' => 'Missing phone',
            'description' => 'Black phone lost near the library.',
            'location' => 'Library',
            'date_lost' => now()->toDateString(),
            'contact' => 'reporter@example.com',
            'user_id' => $reporter->id,
        ]);
        PostItem::create([
            'category' => 'Bags',
            'item_name' => 'Missing backpack',
            'description' => 'Blue backpack lost in class.',
            'location' => 'Classroom',
            'date_lost' => now()->toDateString(),
            'contact' => 'reporter@example.com',
            'user_id' => $reporter->id,
        ]);

        $this->actingAs($admin)
            ->get(route('AdminDashboard'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, no-store, private')
            ->assertSee('Missing phone')
            ->assertSee('Missing backpack')
            ->assertSee('href="' . route('postItem') . '"', false)
            ->assertSee('data-dashboard-count="total">2</p>', false)
            ->assertSee('data-dashboard-count="lost">2</p>', false)
            ->assertSee('data-dashboard-count="found">0</p>', false)
            ->assertSee('data-dashboard-count="claims">0</p>', false)
            ->assertDontSee('No items have been reported');
    }

    public function test_admin_dashboard_count_endpoint_returns_current_database_counts(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        foreach (['Lost phone', 'Lost backpack'] as $name) {
            PostItem::create([
                'category' => 'Electronics',
                'item_name' => $name,
                'description' => 'Lost near the library.',
                'location' => 'Main Library',
                'date_lost' => now()->toDateString(),
            ]);
        }
        PostFoundItems::create([
            'item_name' => 'Blue notebook',
            'category' => 'Books',
            'condition' => 'Good',
            'found_location' => 'Main Library',
            'found_date' => now()->toDateString(),
            'description' => 'Blue notebook found in the library.',
            'finder_name' => 'Finder',
            'phone' => '0712345678',
            'email' => null,
        ]);
        ClaimItems::create([
            'item_name' => 'Blue notebook',
            'claimant_name' => 'Amina User',
            'contact' => 'amina@example.com',
            'description' => 'My name is written inside.',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->getJson(route('AdminDashboard.counts'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, no-store, private')
            ->assertExactJson([
                'lost' => 2,
                'found' => 1,
                'claims' => 1,
            ]);
    }

    public function test_regular_users_cannot_read_admin_dashboard_counts(): void
    {
        $this->actingAs(User::factory()->create())
            ->getJson(route('AdminDashboard.counts'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_admin_dashboard_counts_active_potential_matches_and_displays_their_scores(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        PostItem::create([
            'category' => 'Bags',
            'item_name' => 'Black backpack',
            'description' => 'Black backpack with a red keychain',
            'location' => 'Main Library',
            'date_lost' => '2026-10-25',
        ]);
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

        $this->actingAs($admin)
            ->get(route('AdminDashboard'))
            ->assertOk()
            ->assertSee('Potential matches')
            ->assertSee('Black backpack')
            ->assertSee('100% match')
            ->assertSee('>1</p>', false);
    }

    public function test_admin_dashboard_excludes_claimed_found_reports_from_potential_matches(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        PostItem::create([
            'category' => 'Bags',
            'item_name' => 'Black backpack',
            'description' => 'Black backpack with a red keychain',
            'location' => 'Main Library',
            'date_lost' => '2026-10-25',
        ]);
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
            'status' => 'claimed',
        ]);

        $this->actingAs($admin)
            ->get(route('AdminDashboard'))
            ->assertOk()
            ->assertSee('Potential matches')
            ->assertSee('No potential lost/found matches meet the 35% threshold yet.')
            ->assertSee('>0</p>', false);
    }
}
