ByetHost (या किसी भी फ्री/शेयर्ड होस्टिंग) पर अगर आप Laravel प्रोजेक्ट होस्ट करते हैं और SSL (HTTPS) पहले से लगा हुआ है, तो आप अपनी तरफ से और कैसे सिक्योर कर सकते हैं।

---

## 🔒 Laravel प्रोजेक्ट को और सुरक्षित बनाने के तरीके

### 1. **HTTPS को मजबूरी बनाइए**

* `.htaccess` फ़ाइल में HTTPS redirect डालिए:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

* `AppServiceProvider.php` में भी HTTPS को फोर्स कर सकते हैं:

```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if (env('APP_ENV') !== 'local') {
        URL::forceScheme('https');
    }
}
```

---

### 2. **`.env` फ़ाइल को सुरक्षित रखें**

* `.env` को **public\_html** फोल्डर के बाहर रखिए।
* `.htaccess` में ये नियम डाल दीजिए ताकि कोई ब्राउज़र से `.env` फाइल एक्सेस न कर पाए:

```apache
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

---

### 3. **मजबूत पासवर्ड और हैशिंग**

* Database, Admin और FTP के लिए **strong password** ज़रूर रखें।
* Laravel में पासवर्ड हमेशा `Hash::make()` या `bcrypt()` से स्टोर करें।

---

### 4. **Debug Mode बंद करें**

* प्रोडक्शन में `.env` फाइल में:

```env
APP_DEBUG=false
APP_ENV=production
```

(Debug mode चालू रहेगा तो error में server path और database details लीक हो सकते हैं।)

---

### 5. **Laravel की बिल्ट-इन सिक्योरिटी का उपयोग करें**

* **CSRF protection** – पहले से चालू रहती है
* **XSS protection** – Blade में `{{ $var }}` इस्तेमाल करने से डेटा auto-escape होता है
* **SQL Injection से बचाव** – Eloquent या Query Builder का इस्तेमाल करें

---

### 6. **File & Folder Permissions सही रखें**

* सिर्फ `storage` और `bootstrap/cache` को writable रखें।
* बाकी फोल्डरों के लिए permissions कुछ ऐसे सेट करें:

```bash
chmod -R 755 public
chmod -R 755 bootstrap/cache
chmod -R 755 storage
```

(777 कभी मत लगाइए, बहुत खतरा है।)

---

### 7. **सिक्योरिटी पैकेज का इस्तेमाल**

* Roles & Permissions → `spatie/laravel-permission`
* CORS Control → `fruitcake/laravel-cors`

---

### 8. **डेटाबेस को सुरक्षित रखें**

* Strong password लगाइए
* केवल Eloquent / prepared queries का उपयोग कीजिए
* Remote DB Access बंद रखें (shared hosting में आमतौर पर बंद ही रहता है)

---

### 9. **Login Attempts लिमिट करें**

Laravel में `throttle` middleware से brute-force रोक सकते हैं:

```php
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
```

👉 मतलब: एक मिनट में सिर्फ 5 बार login कोशिश कर सकता है।

---

### 10. **बैकअप ज़रूर बनाइए**

* Free hosting पर बैकअप भरोसेमंद नहीं होते।
* Laravel scheduler में daily database और files का backup स्क्रिप्ट बनाइए और खुद डाउनलोड करके save रखिए।

---

### ⚡ Extra Tip – Cloudflare का इस्तेमाल कीजिए

* Free Cloudflare जोड़ दीजिए →

  * Extra SSL
  * DDoS protection
  * Cache और Speed boost
  * WAF (Web Application Firewall)

इससे ByetHost के SSL के ऊपर एक और security layer मिल जाएगी।

---