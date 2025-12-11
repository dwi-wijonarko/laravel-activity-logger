<?php

namespace DwiWijonarko\ActivityLogger\Facades;

use Illuminate\Support\Facades\Facade;

class Activity extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'activity-logger';
    }
}
