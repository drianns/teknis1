<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ChatTicketUser;
use App\Models\UserAgent;
use App\Models\Company;
use App\Models\Channel;



class ResultTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'genesisnumber',
        'user_id',
        'user_agent_id',
        'channel_id',
        'km_article_id',
        'priority',
        'status',
        'flaging',
        'category_id',
        'subcategory_id',
        'blast_queues_id',
        'ticket_number',
        'ticket_position',
        'payload',
        'is_merged',
        'parent_merge_id',
        'merged_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_merged' => 'boolean',
        'merged_at' => 'datetime',
    ];

    protected $appends = [
        'flaging_label',
    ];

    /**
     * Relasi dinamis berdasarkan nilai flaging
     */
    public function chat_ticket_user(): BelongsTo
    {
        return $this->belongsTo(ChatTicketUser::class, 'user_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }


    public function genesisData()
    {
        return null;
    }



    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function blast_queue_history()
    {
        return $this->belongsTo(BlastQueueHistory::class, 'blast_queues_id');
    }

    public function user_agent()
    {
        return $this->belongsTo(UserAgent::class, 'user_agent_id');
    }

    public function article()
    {
        return $this->belongsTo(KmArticle::class, 'km_article_id');
    }

    public function category()
    {
        return null; // To be implemented with KategoriComplaint
    }

    public function subcategory()
    {
        return null; // To be implemented with JenisComplaint
    }

    public function ticketMerge()
    {
        return null;
    }

    public function mergeAsParent()
    {
        return null;
    }

    public function mergeAsChild()
    {
        return null;
    }

    /**
     * Scope untuk filter hanya ticket yang belum di-merge
     */
    public function scopeNotMerged($query)
    {
        return $query->where('is_merged', 0);
    }

    /**
     * Scope untuk filter hanya ticket yang sudah di-merge
     */
    public function scopeMerged($query)
    {
        return $query->where('is_merged', 1);
    }


    /**
     * Helper untuk menampilkan teks flaging
     */
    public function getFlagingLabelAttribute()
    {
        return match ($this->flaging) {
            1 => 'Inbound',
            2 => 'Outbound',
            3 => 'Chat',
            4 => 'Email',
            5 => 'Blast Thread',
            default => 'Unknown',
        };
    }

    /**
     * Helper untuk check apakah ticket bisa di-merge
     */
    public function canBeMerged(): bool
    {
        return $this->is_merged == 0;
    }

    /**
     * Helper untuk check apakah ticket adalah parent merge
     */
    public function isParentMerge(): bool
    {
        return $this->mergeAsParent() ? $this->mergeAsParent()->exists() : false;
    }

    public function isChildMerge(): bool
    {
        return $this->is_merged == 1 && $this->parent_merge_id !== null;
    }
}
