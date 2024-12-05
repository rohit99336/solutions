Laravel provides methods to handle soft deletes and manage records marked as "deleted." Here’s a comprehensive guide to soft delete-related methods, including **force delete**, **trash**, and **recovery methods**:

---

### 1. **Enable Soft Deletes**

First, ensure your model supports soft deletes:

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YourModel extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
```

In the database migration:

```php
$table->softDeletes(); // Adds `deleted_at` column
```

---

### 2. **Soft Delete Methods**

#### a. **Soft Deleting a Record**

The `delete` method will mark a record as deleted by setting the `deleted_at` column to the current timestamp.

```php
$model = YourModel::find($id);
$model->delete();
```

#### b. **Querying Non-Deleted Records (Default Behavior)**

When soft deletes are enabled, Laravel excludes records with `deleted_at` set.

```php
$records = YourModel::all(); // Only non-deleted records are fetched
```

#### c. **Querying Deleted Records**

To include soft-deleted records, use the `withTrashed` scope.

```php
$records = YourModel::withTrashed()->get(); // Includes deleted records
```

#### d. **Querying Only Deleted Records**

To retrieve only soft-deleted records:

```php
$trashedRecords = YourModel::onlyTrashed()->get();
```

---

### 3. **Force Deleting Records**

Force deleting removes the record entirely from the database.

```php
$model = YourModel::withTrashed()->find($id);
$model->forceDelete();
```

---

### 4. **Recovering Soft-Deleted Records**

The `restore` method restores a soft-deleted record by nullifying the `deleted_at` column.

```php
$model = YourModel::onlyTrashed()->find($id);
$model->restore();
```

---

### 5. **Implementing in a Controller**

```php
use App\Models\YourModel;

class YourController extends Controller
{
    // Soft delete a record
    public function softDelete($id)
    {
        $model = YourModel::find($id);
        $model->delete();
        return back()->with('success', 'Record soft deleted successfully.');
    }

    // View trashed records
    public function trashed()
    {
        $trashedRecords = YourModel::onlyTrashed()->get();
        return view('your_view', compact('trashedRecords'));
    }

    // Restore a soft-deleted record
    public function restore($id)
    {
        $model = YourModel::onlyTrashed()->find($id);
        $model->restore();
        return back()->with('success', 'Record restored successfully.');
    }

    // Force delete a record
    public function forceDelete($id)
    {
        $model = YourModel::withTrashed()->find($id);
        $model->forceDelete();
        return back()->with('success', 'Record permanently deleted.');
    }
}
```

---

### 6. **Blade View Example**

```html
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($trashedRecords as $record)
    <tr>
      <td>{{ $record->id }}</td>
      <td>{{ $record->name }}</td>
      <td>
        <form action="{{ route('restore', $record->id) }}" method="POST">
          @csrf @method('PATCH')
          <button type="submit">Restore</button>
        </form>
        <form action="{{ route('forceDelete', $record->id) }}" method="POST">
          @csrf @method('DELETE')
          <button type="submit">Force Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
```

---

### 7. **Routes**

Define routes for handling these actions:

```php
Route::get('/trashed', [YourController::class, 'trashed'])->name('trashed');
Route::patch('/restore/{id}', [YourController::class, 'restore'])->name('restore');
Route::delete('/force-delete/{id}', [YourController::class, 'forceDelete'])->name('forceDelete');
Route::delete('/soft-delete/{id}', [YourController::class, 'softDelete'])->name('softDelete');
```

---

These methods provide complete control over soft-deleted records in your Laravel application.
