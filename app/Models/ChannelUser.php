<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChannelUser extends Model
{
    use HasFactory;
    protected $fillable = ['account_id', 'channel_id', 'name', 'email', 'photo', 'company_id', 'chat_ticket_user_id'];
    public $appends = ['photo_src'];

    public function getPhotoSrcAttribute()
    {
        return (!empty($this->photo))? $this->photo : asset('/assets/images/icons/user.png');
    }

    /**
     * Get the channel that owns the ChannelUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function chat_ticket_user()
    {
        return $this->belongsTo(ChatTicketUser::class, 'chat_ticket_user_id', 'id');
    }
}
