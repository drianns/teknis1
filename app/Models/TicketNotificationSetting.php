<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketNotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'is_active'
    ];
}
