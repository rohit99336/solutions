# Laravel Artisan Commands

## **Session Commands**
- Generate the session table migration:
  ```sh
  php artisan session:table
  ```

## **Seeding and Database Management**
- Seed a specific class name:
  ```sh
  php artisan db:seed --class=SeederClassName --force
  ```
- Run all migrations:
  ```sh
  php artisan migrate --force
  ```
- Rollback migrations:
  ```sh
  php artisan migrate:rollback --force
  ```
- Run database seeder:
  ```sh
  php artisan db:seed --force
  ```

## **Cache Management**
- Clear application cache:
  ```sh
  php artisan cache:clear
  ```
- Clear route cache:
  ```sh
  php artisan route:clear
  ```
- Clear config cache:
  ```sh
  php artisan config:clear
  ```
- Clear view cache:
  ```sh
  php artisan view:clear
  ```
- Optimize the application:
  ```sh
  php artisan optimize
  ```
- Clear all caches:
  ```sh
  php artisan cache:clear && php artisan route:clear && php artisan config:clear && php artisan view:clear
  ```

## **Queue Management**
- Run queue worker:
  ```sh
  php artisan queue:work --daemon
  ```
- Restart queue:
  ```sh
  php artisan queue:restart
  ```

## **Storage and Logs**
- Link storage:
  ```sh
  php artisan storage:link
  ```
- Clear Laravel log file:
  ```php
  $logFile = storage_path('logs/laravel.log');
  if (File::exists($logFile)) {
      File::put($logFile, '');
      return "Laravel log file has been cleared!";
  }
  return "Log file not found!";
  ```

## **Scheduling**
- Run scheduled tasks:
  ```sh
  php artisan schedule:run
  ```

---

This document contains essential Laravel Artisan commands for managing migrations, cache, queue workers, storage, and logs.

