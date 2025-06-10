Here’s a **cheat sheet** and **usage guide** for `spatie/laravel-permission`, one of the most popular Laravel packages for **role and permission management**.

---

## 🧩 Installation

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Then, add the `Spatie\Permission\Traits\HasRoles` trait to your `User` model:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

---

## 📦 Models

* `Role`
* `Permission`

You can create custom models by publishing the config file:

```bash
php artisan vendor:publish --tag="permission-config"
```

---

## 🛠️ Creating Roles & Permissions

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create roles
Role::create(['name' => 'admin']);
Role::create(['name' => 'user']);

// Create permissions
Permission::create(['name' => 'edit posts']);
Permission::create(['name' => 'delete posts']);
```

---

## 🔗 Assigning Roles & Permissions

### Assign Role to User

```php
$user->assignRole('admin');
$user->assignRole(['admin', 'user']); // Multiple
```

### Remove Role

```php
$user->removeRole('admin');
```

### Give Permission to Role

```php
$role = Role::findByName('admin');
$role->givePermissionTo('edit posts');
```

### Assign Permission Directly to User

```php
$user->givePermissionTo('edit posts');
```

### Revoke Permission

```php
$user->revokePermissionTo('edit posts');
```

---

## ✅ Checking Permissions & Roles

### Role Check

```php
$user->hasRole('admin');
$user->hasAnyRole(['admin', 'editor']);
$user->hasAllRoles(['admin', 'editor']);
```

### Permission Check

```php
$user->can('edit posts'); // Same as $user->hasPermissionTo()
$user->hasPermissionTo('edit posts');
```

---

## ⚙️ Blade Directives

```blade
@role('admin')
    <p>This is visible to admins</p>
@endrole

@hasrole('editor')
    <p>This is visible to editors</p>
@endhasrole

@hasanyrole('writer|admin')
    <p>This is visible to writer or admin</p>
@endhasanyrole

@hasallroles('writer|admin')
    <p>This is visible if user has both writer and admin</p>
@endhasallroles

@can('edit posts')
    <p>User can edit posts</p>
@endcan
```

---

## 📋 Useful Artisan Commands

```bash
php artisan permission:show           # List roles/permissions
php artisan permission:cache-reset    # Reset the cache
```

---

## 🧠 Middleware

Add to your `Http/Kernel.php`:

```php
'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
```

Then use it in routes:

```php
Route::group(['middleware' => ['role:admin']], function () {
    // Only accessible to admin
});
```

---

## 💡 Tips

* Roles and permissions are cached, so use `php artisan permission:cache-reset` after changes.
* Supports teams/multiple guards (via config).
* You can create your own `RolePolicy` or use Laravel’s native gates.

---

Would you like a **GUI admin panel for managing roles/permissions** as well?
