<?php

namespace DwiWijonarko\ActivityLogger\Traits;

use DwiWijonarko\ActivityLogger\Models\Activity;

trait Loggable
{
    protected static function bootLoggable()
    {
        static::created(function ($model) {
            if (config('activity-logger.enabled', true)) {
                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'attributes' => $model->attributesToArray(),
                    ])
                    ->inLog($model->getLogNameForEvent('created'))
                    ->log("Created {$model->getLogDescription('created')}");
            }
        });

        static::updated(function ($model) {
            if (config('activity-logger.enabled', true)) {
                $oldValues = $model->getOriginal();
                $newValues = $model->getChanges();

                $ignoredAttributes = config('activity-logger.ignore_attributes', []);
                $oldValues = array_diff_key($oldValues, array_flip($ignoredAttributes));
                $newValues = array_diff_key($newValues, array_flip($ignoredAttributes));

                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => $oldValues,
                        'attributes' => $newValues,
                    ])
                    ->inLog($model->getLogNameForEvent('updated'))
                    ->log("Updated {$model->getLogDescription('updated')}");
            }
        });

        static::deleted(function ($model) {
            if (config('activity-logger.enabled', true)) {
                activity()
                    ->performedOn($model)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => $model->attributesToArray(),
                    ])
                    ->inLog($model->getLogNameForEvent('deleted'))
                    ->log("Deleted {$model->getLogDescription('deleted')}");
            }
        });
    }

    protected function getLogNameForEvent(string $event): string
    {
        return property_exists($this, 'logName') ? $this->logName : 'default';
    }

    protected function getLogDescription(string $event): string
    {
        $modelName = class_basename($this);
        return "{$modelName}";
    }
}
