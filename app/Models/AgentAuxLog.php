<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentAuxLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    // Duration dalam detik
    public function getDurationSecondsAttribute(): int
    {
        if ($this->start_time && $this->end_time) {
            return (int) $this->start_time->diffInSeconds($this->end_time);
        }
        return 0;
    }

    // Format durasi HH:MM:SS
    public function getDurationFormattedAttribute(): string
    {
        $s = $this->duration_seconds;
        return sprintf('%02d:%02d:%02d', intdiv($s, 3600), intdiv($s % 3600, 60), $s % 60);
    }
}
