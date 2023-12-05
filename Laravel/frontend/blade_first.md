# Laravel Front-end(Blade) file issues and solutions

## 1. How to pass parameter route to blade file

```php
// Route
Route::get('/blade', function () {
    $data = [
        'name' => 'John',
        'age' => 20,
    ];
    return view('blade', $data);
});

// Blade file
{{ $name }}
{{ $age }}
```

## 2. How to pass parameter in get method route blade file to controller

```php
// Route
Route::get('/blade', 'BladeController@index')->name('route_name');

// Controller
public function index(Request $request)
{
    $data = [
        'name' => $request->name,
        'age' => $request->age,
    ];
    return view('blade', $data);
}

// Blade file
 <a href="{{ route('route_name', ['parameter_name' => $parameter]) }}">Link text</a>
```
