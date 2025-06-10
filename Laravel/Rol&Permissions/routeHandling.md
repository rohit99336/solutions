To **handle routes using `spatie/laravel-permission`**, you can **protect routes with middleware** based on:

* Roles
* Permissions
* Roles *or* permissions

Here’s how to properly configure and use it:

---

## ✅ 1. Register Middleware

Spatie’s middleware should be registered (already done automatically in recent Laravel versions), but double-check `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
];
```

---

## 🧭 2. Use Middleware in Routes

### Protect Route by Role

```php
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});
```

### Protect Route by Permission

```php
Route::group(['middleware' => ['permission:edit posts']], function () {
    Route::get('/posts/edit/{id}', [PostController::class, 'edit']);
});
```

### Allow Either Role or Permission

```php
Route::group(['middleware' => ['role_or_permission:admin|edit posts']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

You can also apply it directly on a route:

```php
Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin');
```

---

## 💡 3. Multiple Roles or Permissions

```php
// Any of the listed roles
'middleware' => ['role:admin|manager']
// All listed roles
'middleware' => ['role:admin&manager']

// Any permission
'middleware' => ['permission:edit posts|delete posts']
// All permissions
'middleware' => ['permission:edit posts&delete posts']
```

---

## 🛡 4. Fallback or Unauthorized Handling

You can handle unauthorized access using the built-in exception. In `app/Exceptions/Handler.php`:

```php
use Spatie\Permission\Exceptions\UnauthorizedException;

public function render($request, Throwable $exception)
{
    if ($exception instanceof UnauthorizedException) {
        return response()->view('errors.403', [], 403);
    }

    return parent::render($request, $exception);
}
```

---

## 📝 5. Example with Auth

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-panel', [AdminPanelController::class, 'index']);
});
```

---
