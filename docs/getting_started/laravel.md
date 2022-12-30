# Using Bloom with Laravel

Bloom does not ship with a Laravel integration, however it is very easy to integrate with a new or existing laravel application.

## Configuration

Create a new `bloom.php` configuration file in the `config` directory that includes this basic scaffolding:

```php
<?php

/**
 * Bloom Configuration
 */
return [

    /**
     * Should outgoing HTTP requests be disabled?
     */
    'fake' => false,

    /**
     * The network passphrase to use when hashing signature payloads.
     */
    'network_passphrase' => \StageRightLabs\Bloom\Bloom::TEST_NETWORK_PASSPHRASE,

    /**
     * The URL for the desired Horizon instance.
     */
    'network_url' => \StageRightLabs\Bloom\Bloom::TEST_NETWORK_URL,

    /**
     * Should the use of friendbot be allowed?
     */
    'allow_friendbot' => true,

    /**
     * The URL to use for friendbot funding requests
     */
    'friendbot_url' => \StageRightLabs\Bloom\Bloom::FRIENDBOT_URL,

    /**
     * Enable debug mode
     */
    'debug' => false,

];
```

You can use any Bloom configuration value here.

## Service Provider

Use the artisan console to generate a new service provider:

```bash
php artisan make:provider BloomServiceProvider
```

Now update the contents of the `register` method like so:

```php
/**
 * Register services.
 *
 * @return void
 */
public function register()
{
    $this->app->bind(\StageRightLabs\Bloom\Bloom::class, function () {
        return new \StageRightLabs\Bloom\Bloom;
    });
}
```

You can either use the fully qualified class name or import the class via a `use` statement at the top of the file.

Make sure you register this new service provider in your `config/app.php` file.

With these two files in place you can now use Laravel's dependency injection container to instantiate an instance of the bloom client:

```php
use Illuminate\Console\Command;
use \StageRightLabs\Bloom\Bloom;

class SomeArtisanCommand extends Command
{
    protected Bloom $bloom;

    public function __construct(Bloom $bloom)
    {
        $this->bloom = $bloom;
    }
}
```
