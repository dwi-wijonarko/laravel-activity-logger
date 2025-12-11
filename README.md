# Laravel Activity Logger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dwi-wijonarko/laravel-activity-logger.svg?style=flat-square)](https://packagist.org/packages/dwi-wijonarko/laravel-activity-logger)
[![Total Downloads](https://img.shields.io/packagist/dt/dwi-wijonarko/laravel-activity-logger.svg?style=flat-square)](https://packagist.org/packages/dwi-wijonarko/laravel-activity-logger)

A simple and elegant Laravel package for logging user activities. Track all user actions including create, update, and delete operations with automatic logging.

## Features

- 🚀 **Auto Logging**: Automatically log model events using a simple trait
- 📝 **Manual Logging**: Fluent interface for custom activity logging
- 🔍 **Query Filters**: Easy querying by user, model, date range, etc.
- 💾 **Detailed Tracking**: Stores old and new values, IP address, user agent
- ⚙️ **Configurable**: Customize table name, ignored attributes, retention period
- 🎯 **Laravel 9-11 Support**: Compatible with latest Laravel versions

## Installation

Install via Composer:

```bash
composer require dwi-wijonarko/laravel-activity-logger
```

Publish the config file:

```bash
php artisan vendor:publish --tag=activity-logger-config
```

Run migrations:

```bash
php artisan migrate
```

## Usage

### Automatic Logging with Trait

Add the `Loggable` trait to any model you want to track:

```php
use DwiWijonarko\ActivityLogger\Traits\Loggable;

class Post extends Model
{
    use Loggable;
    
    // Optional: customize log name
    protected $logName = 'posts';
}
```

Now all `created`, `updated`, and `deleted` events will be automatically logged!

### Manual Logging

Use the `activity()` helper for custom logs:

```php
// Simple logging
activity()->log('User viewed dashboard');

// With subject model
activity()
    ->performedOn($post)
    ->log('Post was published');

// With causer (authenticated user by default)
activity()
    ->causedBy($user)
    ->performedOn($post)
    ->log('Admin featured this post');

// With custom properties
activity()
    ->performedOn($post)
    ->withProperties(['custom' => 'data', 'reason' => 'Featured'])
    ->log('Post was featured');

// With log name (for categorization)
activity()
    ->inLog('admin')
    ->performedOn($post)
    ->log('Post was reviewed');
```

### Querying Activity Logs

```php
use DwiWijonarko\ActivityLogger\Models\Activity;

// Get all activities
$activities = Activity::all();

// Get activities for a specific model
$postActivities = Activity::forSubject($post)->get();

// Get activities by a specific user
$userActivities = Activity::forCauser($user)->get();

// Filter by log name
$adminLogs = Activity::inLog('admin')->get();

// Filter by date range
$recentActivities = Activity::between(
    now()->subDays(7),
    now()
)->get();

// Combine filters
$activities = Activity::forCauser($user)
    ->inLog('posts')
    ->between(now()->subMonth(), now())
    ->latest()
    ->paginate(20);
```

### Accessing Log Data

```php
$activity = Activity::first();

// Get the user who performed the action
$user = $activity->causer;

// Get the model that was affected
$subject = $activity->subject;

// Get changes (for update events)
$changes = $activity->getChanges();
$oldValues = $activity->getOldValues();

// Access properties
$customData = $activity->properties;
```

### Relationship Usage

```php
// In your User model
public function activities()
{
    return $this->morphMany(Activity::class, 'causer');
}

// In your Post model
public function activities()
{
    return $this->morphMany(Activity::class, 'subject');
}

// Usage
$user->activities; // All activities by this user
$post->activities; // All activities on this post
```

## Configuration

Edit `config/activity-logger.php`:

```php
return [
    // Enable/disable logging
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),
    
    // Table name
    'table_name' => 'activity_logs',
    
    // Auto-delete logs older than X days
    'delete_after_days' => 90,
    
    // Attributes to ignore when logging updates
    'ignore_attributes' => [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ],
    
    // Log authentication events
    'log_auth' => true,
];
```

## Examples

### Blog Post Management

```php
class Post extends Model
{
    use Loggable;
    
    protected $logName = 'content';
}

// Activities are automatically logged
$post = Post::create(['title' => 'My Post', 'content' => '...']);
// Logs: "Created Post"

$post->update(['title' => 'Updated Title']);
// Logs: "Updated Post" with old and new values

$post->delete();
// Logs: "Deleted Post"
```

### Custom Admin Actions

```php
// When admin approves content
activity()
    ->inLog('admin')
    ->performedOn($post)
    ->causedBy($admin)
    ->withProperties(['status' => 'approved', 'notes' => 'Quality content'])
    ->log('Content approved by admin');
```

### User Activity Timeline

```php
// Display user's activity timeline
$activities = Activity::forCauser($user)
    ->latest()
    ->paginate(20);

foreach ($activities as $activity) {
    echo "{$activity->created_at}: {$activity->description}";
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security-related issues, please email dwi.wijonarko@gmail.com instead of using the issue tracker.

## Credits

- [Dwi Wijonarko](https://github.com/dwi-wijonarko)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
