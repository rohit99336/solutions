
---

## 🔑 तरीका 1: Middleware के ज़रिए (सबसे आसान और साफ-सुथरा तरीका)

Spatie package हमें `permission` middleware देता है।
आप अपने controller के constructor (`__construct`) में इसे इस तरह लगा सकते हो:

```php
<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // index ke liye
        $this->middleware('permission:shopify-cart-index')->only('index');

        // create aur store ke liye
        $this->middleware('permission:shopify-cart-create')->only(['create', 'store']);

        // show ke liye
        $this->middleware('permission:shopify-cart-show')->only('show');

        // edit aur update ke liye
        $this->middleware('permission:shopify-cart-edit')->only(['edit', 'update']);

        // delete ke liye
        $this->middleware('permission:shopify-cart-delete')->only('destroy');
    }

    public function index()
    {
        // yaha user tabhi aa payega
        // jab uske paas "shopify-cart-index" permission hoga
    }

    public function create(Request $request) {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(Request $request) {}
    public function update(Request $request) {}
    public function destroy(string $id) {}
}
```

➡ अगर किसी user के पास required permission नहीं है तो Laravel **403 Forbidden** error देगा।

---

## 🔑 तरीका 2: Method के अंदर manual check

अगर आप चाहो तो हर method के अंदर permission check कर सकते हो:

```php
public function index()
{
    if (!auth()->user()->can('shopify-cart-index')) {
        abort(403, 'आपको इस action की अनुमति नहीं है।');
    }

    // आगे का logic
}
```

---

## 🔑 तरीका 3: Blade (view) में check करना

अगर आपको frontend पर बटन/लिंक छुपाना है तो इस तरह use कर सकते हो:

```blade
@can('shopify-cart-create')
    <a href="{{ route('shopify.checkout.create') }}" class="btn btn-primary">Create Cart</a>
@endcan
```

---

### 📌 Best Practice

हर जगह `if` check लिखने से अच्छा है कि आप **Middleware वाला तरीका (तरीका 1)** use कर।
इससे controller साफ-सुथरा रहेगा और permission check अपने-आप हो जाएगा।

---