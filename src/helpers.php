<?php

use DwiWijonarko\ActivityLogger\ActivityLogger;

if (!function_exists('activity')) {
    function activity(): ActivityLogger
    {
        return app('activity-logger');
    }
}
