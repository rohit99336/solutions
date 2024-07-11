Integrating the Razorpay payment gateway into a Laravel application involves several steps. Below is a step-by-step guide to help you achieve this integration:

### Step 1: Install Razorpay PHP SDK

First, you need to install the Razorpay PHP SDK via Composer. Run the following command in your Laravel project's root directory:

```bash
composer require razorpay/razorpay
```

### Step 2: Configure Razorpay API Keys

Next, add your Razorpay API keys to your environment file (`.env`). You can obtain these keys from your Razorpay dashboard.

```dotenv
RAZORPAY_KEY_ID=your_key_id
RAZORPAY_KEY_SECRET=your_key_secret
```

### Step 3: Create Razorpay Service

Create a new service provider for Razorpay. You can generate a service provider using the following Artisan command:

```bash
php artisan make:provider RazorpayServiceProvider
```

In the generated `RazorpayServiceProvider` file, add the following code to register the Razorpay service:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Razorpay\Api\Api;

class RazorpayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Api::class, function ($app) {
            return new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
```

Then, register the provider in `config/app.php`:

```php
'providers' => [
    // ...
    App\Providers\RazorpayServiceProvider::class,
],
```

### Step 4: Update Services Configuration

Add the Razorpay configuration to `config/services.php`:

```php
'razorpay' => [
    'key_id' => env('RAZORPAY_KEY_ID'),
    'key_secret' => env('RAZORPAY_KEY_SECRET'),
],
```

### Step 5: Create Payment Controller

Generate a payment controller to handle the payment process:

```bash
php artisan make:controller PaymentController
```

In the `PaymentController`, add the following methods:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function initiatePayment()
    {
        return view('payment');
    }

    public function processPayment(Request $request)
    {
        $api = app(Api::class);
        
        $orderData = [
            'receipt'         => 'order_rcptid_11',
            'amount'          => $request->amount * 100, // amount in the smallest currency unit
            'currency'        => 'INR'
        ];

        $razorpayOrder = $api->order->create($orderData);

        $data = [
            "key"               => config('services.razorpay.key_id'),
            "amount"            => $request->amount * 100,
            "name"              => "Your Company Name",
            "description"       => "Order #".$razorpayOrder['id'],
            "prefill"           => [
                "name"              => $request->name,
                "email"             => $request->email,
                "contact"           => $request->contact,
            ],
            "order_id"          => $razorpayOrder['id'],
        ];

        return view('confirmPayment', compact('data'));
    }

    public function paymentSuccess(Request $request)
    {
        // Validate the request here

        return "Payment Successful";
    }

    public function paymentFailed()
    {
        return "Payment Failed";
    }
}
```

### Step 6: Create Payment Views

Create views for initiating the payment and confirming the payment. Create a `resources/views/payment.blade.php` file:

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body>
    <form action="{{ route('processPayment') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name">
        <input type="email" name="email" placeholder="Email">
        <input type="text" name="contact" placeholder="Contact">
        <input type="number" name="amount" placeholder="Amount">
        <button type="submit">Pay Now</button>
    </form>
</body>
</html>
```

Create a `resources/views/confirmPayment.blade.php` file:

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <form id="paymentForm">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>
    <button id="rzp-button1">Pay with Razorpay</button>
    <script>
        var options = @json($data);
        
        options.handler = function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('paymentForm').submit();
        };
        
        var rzp1 = new Razorpay(options);
        
        document.getElementById('rzp-button1').onclick = function(e){
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>
```

### Step 7: Define Routes

Add the necessary routes in `routes/web.php`:

```php
use App\Http\Controllers\PaymentController;

Route::get('/payment', [PaymentController::class, 'initiatePayment'])->name('initiatePayment');
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('processPayment');
Route::post('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('paymentSuccess');
Route::get('/payment-failed', [PaymentController::class, 'paymentFailed'])->name('paymentFailed');
```

### Step 8: Add Webhook for Payment Verification (Optional)

To ensure the payment is verified correctly, you might want to add a webhook endpoint that Razorpay can call. This ensures that the payment status is verified and updated in your system.

Add a route in `routes/web.php`:

```php
Route::post('/webhook', [PaymentController::class, 'handleWebhook'])->name('handleWebhook');
```

In `PaymentController`, add the `handleWebhook` method:

```php
public function handleWebhook(Request $request)
{
    // Handle Razorpay webhook data here

    return response()->json(['status' => 'success']);
}
```

### Conclusion

Following these steps will help you integrate Razorpay payment gateway into your Laravel application. Be sure to test the payment flow thoroughly in both test and live environments to ensure everything works correctly. links for helps -

1. 	[Razorpay api](https://razorpay.com/docs/api)
2.  [Razorpay integration](https://razorpay.com/integrations)
3.  [PHP sdk integration](https://razorpay.com/docs/payments/server-integration/php/install/)
4.  [Razorpay sdk api](https://github.com/razorpay/razorpay-php/blob/master/documents/order.md)
5.  [Razorpay Key & Id](https://dashboard.razorpay.com/app/website-app-settings/api-keys)
6.  [Test Numbers]( https://razorpay.com/docs/payments/payments/test-card-details)