<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\ChatHeader;
use App\Models\GroupRouteAgent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAgent extends Model
{
    use HasFactory;
    protected $fillable = ['username', 'user_id', 'company_id', 'in_handled', 'last_handled_at', 'aux', 'alias', 'user_type', 'max_handle', 'max_handle_comment'];

    /**
     * Get the company that owns the UserAgent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    public function getLayerAttribute()
    {
        if (is_null($this->user_type)) {
            return 'layer1';
        }

        switch ($this->user_type) {
            case 'l2':
                return 'layer2';
            case 'l3':
                return 'layer3';
            default:
                return 'layer1';
        }
    }

    /**
     * Get the user that owns the UserAgent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function groupRouteAgents()
    {
        return $this->hasMany(GroupRouteAgent::class, 'agent_id', 'user_id');
    }

    public function getCurrentHandleAttribute()
    {
        return ChatHeader::where('handle_by', $this->user_id)->where('status', 'open')->count();
    }

    public function getClosedChatTodayAttribute()
    {
        return 0; // Placeholder for legacy metrics
    }



    /**
     * Check if user is in specific layer
     *
     * @param string $layer
     * @return bool
     */
    public function isLayer(string $layer): bool
    {
        return $this->layer === $layer;
    }

    /**
     * Check if user is layer1
     *
     * @return bool
     */
    public function isLayer1(): bool
    {
        return $this->isLayer('layer1');
    }

    /**
     * Check if user is layer2
     *
     * @return bool
     */
    public function isLayer2(): bool
    {
        return $this->isLayer('layer2');
    }

    /**
     * Check if user is layer3
     *
     * @return bool
     */
    public function isLayer3(): bool
    {
        return $this->isLayer('layer3');
    }



}
