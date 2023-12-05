# Laravel Front-end(Blade) layout basics

## 1. How to use variable in blade file
```php
{{ $variable }}
```

## 2. How to use php code in blade file
```php
@php
    // php code
@endphp
```

## 3. How to use if condition in blade file
```php
@if (condition)
    // code
@elseif (condition)
    // code
@else

@endif
```

## 4. How to use foreach loop in blade file
```php
@foreach ($array as $item)
    // code
@endforeach

@foreach ($array as $key => $value)
    // code
@endforeach

@forelse ($array as $item)
    // code
@empty
    // code
@endforelse
```

## 5. How to use for loop in blade file
```php
@for ($i = 0; $i < 10; $i++)
    // code
@endfor
```

## 6. How to use while loop in blade file
```php
@while (condition)
    // code
@endwhile
```

## 7. How to use switch case in blade file
```php
@switch($variable)
    @case(1)
        // code
        @break

    @case(2)
        // code
        @break

    @default
        // code
@endswitch
```

## 8. How to use include file in blade file
```php
@include('file_name')
```

## 9. How to use include file with variable in blade file
```php
@include('file_name', ['variable' => $variable])
```

## 10. How to use include file with variable and condition in blade file
```php
@include('file_name', ['variable' => $variable, 'condition' => $condition])
```

## 11. How to use extends file with section in blade file
```php
@extends('file_name')

@section('section_name')
    // code
@endsection
```