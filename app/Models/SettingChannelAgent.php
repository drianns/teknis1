<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingChannelAgent extends Model
{
    use HasFactory;

    protected $table = 'setting_channel_agents';

    protected $fillable = [
        'user_id',
        'menu',
        'sub_menu',
        'detail_menu',
        'url',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
