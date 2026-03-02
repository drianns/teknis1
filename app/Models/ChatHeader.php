<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatHeader extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(ChatHeaderTicket::class, 'chat_header_id');
    }

    public function latestTicket(): HasOne
    {
        return $this->hasOne(ChatHeaderTicket::class, 'chat_header_id')
            ->with(['userAgent.user'])
            ->latestOfMany();
    }
}
