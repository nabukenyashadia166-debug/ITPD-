<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ClaimItems extends Model
{
    protected $fillable = [
        'user_id',
        'found_item_id',
        'item_reference',
        'item_name',
        'category',
        'claimant_name',
        'student_id',
        'contact',
        'email',
        'description',
        'lost_location',
        'additional_info',
        'proof_path',
        'status',
    ];

    public function foundItem(): BelongsTo
    {
        return $this->belongsTo(PostFoundItems::class, 'found_item_id');
    }
}
