<?php
require_once __DIR__ . '/../vendor/autoload.php';

try {
    (Dotenv\Dotenv::create(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->instance('path.config', app()->basePath() . DIRECTORY_SEPARATOR . 'config');
$app->instance('path.storage', app()->basePath() . DIRECTORY_SEPARATOR . 'storage');


/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->register(App\Providers\AppServiceProvider::class);
$app->register(\Illuminate\Redis\RedisServiceProvider::class);
$app->register(\Illuminate\Notifications\NotificationServiceProvider::class);
$app->alias('mailer', \Illuminate\Contracts\Mail\Mailer::class);
$app->register(App\Providers\RouteServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(App\Providers\BroadcastServiceProvider::class);


/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
