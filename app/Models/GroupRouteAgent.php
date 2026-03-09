<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupRouteAgent extends Model
{
    protected $fillable = ['group_route_id', 'agent_id', 'company_id'];

    public function groupRoute(): BelongsTo
    {
        return $this->belongsTo(GroupRoute::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(UserAgent::class, 'agent_id', 'user_id');
    }
}
