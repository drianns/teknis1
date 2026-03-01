<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailMenuApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_name',
        'sub_menu_name',
        'detail_menu_name',
        'url',
        'type',
    ];
}
