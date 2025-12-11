<?php

namespace DwiWijonarko\ActivityLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $guarded = [];

    protected $casts = [
        'properties' => 'array',
    ];

    public function getTable()
    {
        return config('activity-logger.table_name', 'activity_logs');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeForSubject($query, Model $subject)
    {
        return $query->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey());
    }

    public function scopeForCauser($query, Model $causer)
    {
        return $query->where('causer_type', get_class($causer))
            ->where('causer_id', $causer->getKey());
    }

    public function scopeInLog($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }

    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function getChanges(): array
    {
        return $this->properties['attributes'] ?? [];
    }

    public function getOldValues(): array
    {
        return $this->properties['old'] ?? [];
    }
}
