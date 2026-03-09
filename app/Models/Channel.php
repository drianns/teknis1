<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Channel extends Model
{
    use HasFactory;
    public $appends = ['icon_src'];

    public function getIconSrcAttribute()
    {
        return (!empty($this->icon))? asset($this->icon) : asset('/assets/images/icons/user.png');
    }

    public function getAccountTypeAttribute()
    {
        if($this->code == "fb") return "page";
        if($this->code == "ig") return "account";
        if($this->code == "telegram") return "page";
        if($this->code == "whatsapp") return "page";
        if($this->code == "qiscus-whatsapp") return "page";
    }

    public function getAccountByTypeAttribute()
    {
        if($this->code == "fb") return $this->channel_pages()->where('channel_id', $this->id);
        if($this->code == "ig") return $this->channel_accounts()->where('channel_id', $this->id);
        if($this->code == "telegram") return $this->channel_pages()->where('channel_id', $this->id);
        if($this->code == "whatsapp") return $this->channel_pages()->where('channel_id', $this->id);
        if($this->code == "qiscus-whatsapp") return $this->channel_pages()->where('channel_id', $this->id);
    }

    /**
     * Get all of the channel_accounts for the Channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channel_accounts(): HasMany
    {
        return $this->hasMany(ChannelAccount::class);
    }

    /**
     * Get all of the channel_pages for the Channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channel_pages(): HasMany
    {
        return $this->hasMany(ChannelPage::class);
    }
}
