# How to solve laravel-filemanager image not showing issue

### To resolve the "laravel-filemanager image not showing" issue in Laravel, follow these steps:

1. Check the laravel-filemanager package: <br>

   Ensure that the unisharp/laravel-filemanager package is installed correctly in your Laravel project. Run the following command in your terminal at the root of your project:

```bash
composer require unisharp/laravel-filemanager
```

2. Check the laravel-filemanager configuration: <br>

   Ensure that the unisharp/laravel-filemanager package is configured correctly in your Laravel project. Run the following command in your terminal at the root of your project:

```bash
php artisan vendor:publish --tag=lfm_config
```

3. Check the laravel-filemanager assets: <br>

   Ensure that the unisharp/laravel-filemanager package assets are published correctly in your Laravel project. Run the following command in your terminal at the root of your project:

```bash
php artisan vendor:publish --tag=lfm_public
```

4. Check the laravel-filemanager service provider: <br>

   Ensure that the Unisharp\Laravelfilemanager\LaravelFilemanagerServiceProvider::class service provider is listed in the providers section of your config/app.php file. If it is not listed, add it manually:

```bash
'providers' => [
    // ...
    UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
]
```

5. Check the laravel-filemanager alias: <br>

   Ensure that the 'Image' => Intervention\Image\Facades\Image::class alias is listed in the aliases section of your config/app.php file. If it is not listed, add it manually:

```bash
'aliases' => [
    // ...
    'Image' => Intervention\Image\Facades\Image::class,
]
```

6. Check the laravel-filemanager middleware: <br>

   Ensure that the 'filemanager' => \UniSharp\LaravelFilemanager\Middleware\CreateDefaultFolder::class middleware is listed in the routeMiddleware section of your app/Http/Kernel.php file. If it is not listed, add it manually:

```bash
protected $routeMiddleware = [
    // ...
    'filemanager' => \UniSharp\LaravelFilemanager\Middleware\CreateDefaultFolder::class,
];
```

7. (optional) Run commands to clear cache : <br>

```bash
php artisan route:clear
php artisan config:clear
```

8. Create symbolic link :: <br>

```bash
php artisan storage:link
```

9. Edit APP_URL in .env

```bash
APP_URL=http://localhost:8000
// or
APP_URL=yourdomain.com
```

10. Create route group to wrap package routes

```bash
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'filemanager', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
```

## If Image not showing in public folder or Images in view then follow these steps:

1. Solution 1 - Edit APP_URL in .env file

```bash
 if you work in local then change

APP_URL=http://localhost:8000
```

to

```bash
APP_URL=http://127.0.0.1:8000
```

If you work in server then change

```bash
APP_URL=domain_name
```

to

```bash
APP_URL=ip_address
```

2. Solution2 -   Edit config/filesystems.php

```bash
'disks' => [
    // ...
    'public' => [
        'driver' => 'local',
        'root' => public_path('storage'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    'public_uploads' => [
        'driver' => 'local',
        'root' => public_path('uploads'),
        'url' => env('APP_URL').'/uploads',
        'visibility' => 'public',
    ],
],
```
