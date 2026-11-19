<?php

namespace Tests\Feature;

use App\Models\ClaimItems;
use App\Models\PostFoundItems;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClaimVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_review_and_approve_a_claim_and_mark_the_item_claimed(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $foundItem = $this->createFoundItem();
        $claim = $this->createClaim($foundItem);

        $this->actingAs($admin)
            ->get(route('claimVerification'))
            ->assertOk()
            ->assertSee('Claimed by');

        $this->get(route('claimVerification.show', $claim))
            ->assertOk()
            ->assertSee('My name is written inside.')
            ->assertSee('Download ownership evidence')
            ->assertSee('Approve claim')
            ->assertSee('Reject claim');
        $this->get(route('claimVerification.evidence', $claim))
            ->assertOk();

        $this->patch(route('claimVerification.update', $claim), [
            'status' => 'approved',
        ])->assertRedirect(route('claimVerification.show', $claim));

        $this->assertDatabaseHas('claim_items', [
            'id' => $claim->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('post_found_items', [
            'id' => $foundItem->id,
            'status' => 'claimed',
        ]);
    }

    public function test_admin_can_reject_a_claim_without_changing_found_item_availability(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $foundItem = $this->createFoundItem();
        $claim = $this->createClaim($foundItem);

        $this->actingAs($admin)
            ->patch(route('claimVerification.update', $claim), [
                'status' => 'rejected',
            ])
            ->assertRedirect(route('claimVerification.show', $claim));

        $this->assertDatabaseHas('claim_items', [
            'id' => $claim->id,
            'status' => 'rejected',
        ]);
        $this->assertDatabaseHas('post_found_items', [
            'id' => $foundItem->id,
            'status' => 'active',
        ]);
    }

    public function test_non_admin_cannot_open_claim_review_or_download_evidence(): void
    {
        $user = User::factory()->create();
        $claim = $this->createClaim($this->createFoundItem());

        $this->actingAs($user)
            ->get(route('claimVerification'))
            ->assertRedirect(route('dashboard'));

        $this->get(route('claimVerification.evidence', $claim))
            ->assertRedirect(route('dashboard'));
    }

    private function createFoundItem(): PostFoundItems
    {
        return PostFoundItems::create([
            'item_name' => 'Blue notebook',
            'category' => 'Books',
            'condition' => 'Good',
            'found_location' => 'Main Library',
            'found_date' => now()->toDateString(),
            'description' => 'Blue notebook found in the library.',
            'finder_name' => 'Finder',
            'phone' => '0712345678',
            'email' => null,
            'status' => 'active',
        ]);
    }

    private function createClaim(PostFoundItems $foundItem): ClaimItems
    {
        Storage::fake('local');
        Storage::disk('local')->put('claim-proofs/proof.pdf', 'test evidence');

        return ClaimItems::create([
            'found_item_id' => $foundItem->id,
            'item_name' => $foundItem->item_name,
            'category' => $foundItem->category,
            'claimant_name' => 'Amina User',
            'student_id' => 'ST123',
            'contact' => 'amina@example.com',
            'description' => 'My name is written inside.',
            'proof_path' => 'claim-proofs/proof.pdf',
            'status' => 'pending',
        ]);
    }
}
