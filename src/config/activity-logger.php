<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Activity Logging
    |--------------------------------------------------------------------------
    |
    | This option controls whether activity logging is enabled or disabled.
    |
    */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Activity Logs Table Name
    |--------------------------------------------------------------------------
    |
    | The name of the table where activity logs will be stored.
    |
    */
    'table_name' => 'activity_logs',

    /*
    |--------------------------------------------------------------------------
    | Delete Old Logs After Days
    |--------------------------------------------------------------------------
    |
    | Automatically delete activity logs older than specified days.
    | Set to null to disable auto-deletion.
    |
    */
    'delete_after_days' => 90,

    /*
    |--------------------------------------------------------------------------
    | Ignored Attributes
    |--------------------------------------------------------------------------
    |
    | Attributes that should not be logged when models are updated.
    |
    */
    'ignore_attributes' => [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Authentication Events
    |--------------------------------------------------------------------------
    |
    | Enable logging of user authentication events (login, logout, etc).
    |
    */
    'log_auth' => true,
];
