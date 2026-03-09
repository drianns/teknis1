<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class TicketNotificationUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'is_ticket_create',
        'is_ticket_over_sla',
        'is_ticket_closed',
        'is_ticket_escalation'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
