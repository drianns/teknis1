<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatTicketUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'address',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function channel_user()
    {
        return $this->hasMany(ChannelUser::class);
    }
}
