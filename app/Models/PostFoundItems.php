<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostFoundItems extends Model
{
    protected $fillable = [
        'item_name',
        'category',
        'condition',
        'found_location',
        'found_date',
        'description',
        'item_photo',
        'finder_name',
        'phone',
        'email',
        'user_id',
        'status',
    ];
}
