<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


//===============================================================================//
//                                  Commands                                   //
//===============================================================================//
// Artisan commands //
Route::get('/generate-session-table', function () {
    Artisan::call('session:table');
    return "Session table migration generated!";
})->name('generate-session-table');

// seed specific class name data
Route::get('/seed-data/{data}', function ($data) {
    Artisan::call('db:seed', ['--class' => $data, '--force' => true]);
    return "Seeders have been run!";
})->name('seed-data');

// Migration command
Route::get('/run-migrations', function () {
    Artisan::call('migrate', ["--force" => true]);
    return "Migrations have been run!";
})->name('run-migrations');

// Clear application cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Application cache cleared!";
})->name('clear-cache');

// Clear route cache
Route::get('/clear-route', function () {
    Artisan::call('route:clear');
    return "Route cache cleared!";
})->name('clear-route');

// Clear config cache
Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    return "Config cache cleared!";
})->name('clear-config');

// Clear view cache
Route::get('/clear-view', function () {
    Artisan::call('view:clear');
    return "View cache cleared!";
})->name('clear-view');

// Optimize the application
Route::get('/optimize', function () {
    Artisan::call('optimize');
    return "Application optimized!";
})->name('optimize');

// Rollback migrations
Route::get('/rollback-migrations', function () {
    Artisan::call('migrate:rollback', ["--force" => true]);
    return "Migrations have been rolled back!";
})->name('rollback-migrations');

// Seed the database
Route::get('/run-seeder', function () {
    Artisan::call('db:seed', ["--force" => true]);
    return "Database seeding completed!";
})->name('run-seeder');

// Run queue worker
Route::get('/queue-work', function () {
    Artisan::call('queue:work', ["--daemon" => true]);
    return "Queue worker is now running!";
})->name('queue-work');

// Link storage
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "Storage linked successfully!";
})->name('storage-link');

// Clear all caches (shortcut)
Route::get('/clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "All caches have been cleared!";
})->name('clear-all');

// Restart queue
Route::get('/queue-restart', function () {
    Artisan::call('queue:restart');
    return "Queue restarted!";
})->name('queue-restart');

// Run the schedule
Route::get('/schedule-run', function () {
    Artisan::call('schedule:run');
    return "Schedule executed!";
})->name('schedule-run');

// Clear Laravel log file
Route::get('/clear-log', function () {
    $logFile = storage_path('logs/laravel.log');

    if (File::exists($logFile)) {
        File::put($logFile, ''); // Clear the log file content
        return "Laravel log file has been cleared!";
    }

    return "Log file not found!";
})->name('clear-log');
