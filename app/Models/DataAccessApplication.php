<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAccessApplication extends Model
{
    protected $fillable = [
        'level_user', 'menu_level1', 'menu_level2', 'menu_level3', 
        'description', 'created_by'
    ];
}
