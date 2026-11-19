<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostItem extends Model
{
    protected $fillable = [
        'category',
        'item_name',
        'description',
        'location',
        'date_lost',
        'contact',
        'photo_path',
        'user_id',
        'status',
    ];
}
