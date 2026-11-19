<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $fillable = [
        'Account type',
        'full name',
        'email address',
        'phone number',
        'password',
        'confirm password',
    ];
}
