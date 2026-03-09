<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApplication extends Model
{
    protected $fillable = [
        'user_name', 'name', 'email', 'password', 'level_user', 
        'department', 'group_agent', 'site', 'status', 
        'channels', 'description', 'photo_url'
    ];

    protected $casts = [
        'channels' => 'array',
    ];
}
