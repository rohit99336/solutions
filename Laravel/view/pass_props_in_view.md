**AppServiceProvider** में Laravel का **View Composer** इस्तेमाल करके WebMaster की सारी properties को **हर view** में पास कर सकते हैं।

### ✅ तरीका 1: (Simple & Recommended) — Global View Share

अगर सिर्फ एक record है (single row in WebMaster table), तो simply global share कर दो:

---

## ✅ **AppServiceProvider.php**

```php
public function boot(): void
{
    // Global Webmaster Data
    $webmaster = WebMaster::first(); // get single row

    view()->share('webmaster', $webmaster);
}
```

---

## 🔥 अब किसे भी Blade file में सीधे ऐसे उपयोग कर सकते हैं:

### Example in any blade:

```blade
<img src="{{ asset($webmaster->hero_image) }}">
<h1>{{ $webmaster->hero_title }}</h1>
<p>{{ $webmaster->hero_subtitle }}</p>
```

कोई extra पास करने की जरूरत नहीं।

---

# ✅ तरीका 2: View Composer (अगर specific views में ही चाहिए)

---

## **App\Providers\AppServiceProvider.php**

```php
use Illuminate\Support\Facades\View;
use App\Models\WebMaster;

public function boot(): void
{
    View::composer('*', function ($view) {
        $view->with('webmaster', WebMaster::first());
    });
}
```

`'*'` यानी सभी views में available होगा।

---

# 🟦 दोनों तरीकों का फायदा

✔ Blade में बार-बार controller से पास नहीं करना
✔ header, footer, layout, sidebar सबमें easy access
✔ SEO tags / social links / site info कहीं भी इस्तेमाल कर सकते

---

# ❗Important Suggestion

अगर WebMaster table में सिर्फ **1 ही row होनी चाहिए**, तो हमेशा:

```php
WebMaster::first()
```

का ही उपयोग करना चाहिए।

---