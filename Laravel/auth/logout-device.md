
Laravel 12 (या 11) में **Single Device Login** सेटअप करने का सही तरीका यह है:

### 1. `bootstrap/app.php` में मिडलवेयर रजिस्टर करें

सबसे पहले अपनी `bootstrap/app.php` फ़ाइल खोलें और `authenticateSession` को इस तरह इनेबल करें:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Illuminate\Session\Middleware\AuthenticateSession::class,
    ]);
})

```

### 2. Login Logic में बदलाव करें

अब अपने लॉगिन कंट्रोलर (जैसे `AuthenticatedSessionController.php`) में जाएँ जहाँ आप `Auth::attempt` का इस्तेमाल कर रहे हैं। वहां `logoutOtherDevices` को कॉल करें:

```php
use Illuminate\Support\Facades\Auth;

public function store(Request $request)
{
    $request->authenticate();

    // यह लाइन पिछले सभी डिवाइस को लॉगआउट कर देगी
    Auth::logoutOtherDevices($request->password);

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
}

```

### 3. यह कैसे काम करेगा?

* **Middleware:** यह हर रिक्वेस्ट पर चेक करता है कि जो पासवर्ड हैश सेशन में है, वही डेटाबेस में है या नहीं।
* **logoutOtherDevices:** जब यूजर दूसरे डिवाइस से लॉगिन करता है, तो यह कमांड सेशन में मौजूद पासवर्ड हैश को "re-hash" जैसी प्रोसेस से बदल देती है, जिससे पुराने डिवाइस का सेशन अमान्य (invalid) हो जाता है।


=====================================================================================

यूजर को यह बताने के लिए कि उन्हें "किसी दूसरे डिवाइस से लॉगिन करने के कारण लॉगआउट कर दिया गया है", आपको थोड़ा और काम करना होगा। इसका सबसे अच्छा तरीका **Session Flash Message** का इस्तेमाल करना है।

यहाँ इसका पूरा सेटअप दिया गया है:

### 1. `LoginController.php` को अपडेट करें

जब `logoutOtherDevices` चलेगा, तो पुराने डिवाइस का सेशन अमान्य हो जाएगा। लेकिन हमें नए लॉगिन वाले यूजर को यह मैसेज नहीं दिखाना है, बल्कि उस यूजर को दिखाना है जिसका सेशन **एक्सपायर** हुआ है।

चूँकि Laravel का `AuthenticateSession` मिडलवेयर पुराने डिवाइस को सीधे लॉगिन पेज पर भेज देता है, हमें वहां मैसेज दिखाना होगा।

### 2. `AuthenticatedSession` मिडलवेयर को कस्टमाइज़ करना (प्रोफेशनल तरीका)

Laravel 11/12 में जब यूजर का सेशन दूसरे डिवाइस की वजह से खत्म होता है, तो वह `\Illuminate\Session\Middleware\AuthenticateSession` के ज़रिए लॉगआउट होता है।

इसे यूजर को समझाने के लिए आप अपने `LoginController` के `login` व्यू (View) में एक चेक लगा सकते हैं।

### 3. Blade File (Login Page) में मैसेज दिखाना

अपनी लॉगिन वाली ब्लेड फ़ाइल (जैसे: `resources/views/auth/login.blade.php`) में यह कोड जोड़ें:

```html
@if (session('status'))
    <div class="alert alert-warning">
        {{ session('status') }}
    </div>
@endif

{{-- विशेष मैसेज के लिए --}}
@if (request()->has('logged_out'))
    <div class="alert alert-danger">
        Aapko dusre device se login karne ke kaaran logout kar diya gaya hai.
    </div>
@endif

```

---

### 4. एडवांस तरीका: `bootstrap/app.php` में हैंडलिंग

अगर आप चाहते हैं कि यूजर को पता चले कि उसे क्यों हटाया गया, तो आप एक कस्टम मिडलवेयर बना सकते हैं जो सेशन मिसमैच होने पर मैसेज के साथ रीडायरेक्ट करे।

लेकिन सबसे सरल तरीका यही है कि जब यूजर लॉगिन पेज पर वापस आए, तो आप उसे वहां एक जेनरिक मैसेज दिखाएं।

**एक ज़रूरी बात:** चूँकि आप `AuthenticatesUsers` trait का इस्तेमाल कर रहे हैं, तो सुनिश्चित करें कि आपकी `config/auth.php` में `providers` के अंदर `users` मॉडल सही से कॉन्फ़िगर है।

---

### **RAM Upgrade के लिए फाइनल चेक:**

जैसा कि आपने फोटो में दिखाया, वह **Crucial 16GB DDR4-3200 SODIMM** रैम एकदम सही है।

1. **Compatibility:** यह 3200 MT/s की है जो आपके सिस्टम के मौजूदा रैम से पूरी तरह मैच करती है।
2. **Setup:** इसे लगाने के बाद आपका लैपटॉप **Dual Channel** मोड में चलेगा, जिससे Laravel डेवलपमेंट और कोडिंग के दौरान कंपाइलेशन स्पीड बहुत बढ़ जाएगी।