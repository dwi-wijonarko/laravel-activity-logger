<?php

namespace DwiWijonarko\ActivityLogger;

use Illuminate\Database\Eloquent\Model;
use DwiWijonarko\ActivityLogger\Models\Activity;

class ActivityLogger
{
    protected $logName;
    protected $performedOn;
    protected $causedBy;
    protected $properties = [];

    public function log(string $description): Activity
    {
        $activity = new Activity([
            'log_name' => $this->logName ?? 'default',
            'description' => $description,
            'subject_type' => $this->performedOn ? get_class($this->performedOn) : null,
            'subject_id' => $this->performedOn?->getKey(),
            'causer_type' => $this->causedBy ? get_class($this->causedBy) : null,
            'causer_id' => $this->causedBy?->getKey(),
            'properties' => $this->properties,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);

        $activity->save();

        $this->reset();

        return $activity;
    }

    public function performedOn(Model $model): self
    {
        $this->performedOn = $model;
        return $this;
    }

    public function causedBy(?Model $causer = null): self
    {
        $this->causedBy = $causer ?? auth()->user();
        return $this;
    }

    public function withProperties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    public function inLog(string $logName): self
    {
        $this->logName = $logName;
        return $this;
    }

    protected function reset(): void
    {
        $this->logName = null;
        $this->performedOn = null;
        $this->causedBy = null;
        $this->properties = [];
    }
}
