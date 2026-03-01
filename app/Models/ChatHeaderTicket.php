<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ChatHeaderTicket extends Model
{
    use HasFactory;

    protected $table = 'chat_header_tickets';

    protected $fillable = [
        'chat_header_id',
        'chat_ticket_user_id',
        'user_agent_id',
        'km_article_id',
        'priority',
        'status',
        'subject',
        'category',
        'subcategory',
        'question',
        'answer',
        'need_escalated',
        'ticket_number',
        'company_id',
        'source_type'
    ];

    // protected $with = ['company', 'chat_ticket_user', 'user_agent'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function chat_ticket_user(): BelongsTo
    {
        return $this->belongsTo(ChatTicketUser::class, 'chat_ticket_user_id');
    }

    public function chat_header()
    {
        return null;
    }

    public function article()
    {
        return null;
    }

    public function ticketingDetails()
    {
        return null;
    }

    public function attachments()
    {
        return null;
    }
}
