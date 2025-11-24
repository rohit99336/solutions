**AppServiceProvider** में किसी **specific layout blade** को target करके सिर्फ उसी file में data pass कर सकते हैं।

Laravel में इसके लिए **View::composer()** ही best तरीका है।

---

# ✅ **1) सिर्फ किसी एक layout (जैसे: admin.layouts.app)** को डेटा पास करना

उदाहरण: आपको WebMaster data सिर्फ अपने layout **admin/layouts/app.blade.php** में ही पास करना है।

---

## 🔧 **AppServiceProvider.php**

```php
use Illuminate\Support\Facades\View;
use App\Models\WebMaster;

public function boot(): void
{
    View::composer('admin.layouts.app', function ($view) {
        $view->with('webmaster', WebMaster::first());
    });
}
```

---

# 📌 अब सिर्फ उसी layout blade में उपयोग कर पाएँगे

### admin/layouts/app.blade.php

```blade
<title>{{ $webmaster->hero_title ?? 'My Website' }}</title>

<img src="{{ asset($webmaster->hero_image) }}" alt="">
```

❗बाकी pages में $webmaster उपलब्ध नहीं होगा (जब तक कि वे उस layout से extend न करें)।

---

# ✅ **2) किसी layout + उसके children views को pass करना**

यदि आपका layout extend होता है, जैसे:

```
resources/views/admin/layouts/app.blade.php
resources/views/admin/dashboard.blade.php
resources/views/admin/something.blade.php
```

तो composer में बस ये लिखो:

```php
View::composer('admin.*', function ($view) {
    $view->with('webmaster', WebMaster::first());
});
```

अब admin folder के अंदर जितने भी views हैं, उन सबको WebMaster data मिल जाएगा।

---

# 🎯 Summary

| Target                     | View Composer Code                         |
| -------------------------- | ------------------------------------------ |
| सिर्फ एक layout            | `View::composer('admin.layouts.app', ...)` |
| पूरे admin folder के views | `View::composer('admin.*', ...)`           |
| पूरे frontend के views     | `View::composer('frontend.*', ...)`        |
| हर view                    | `View::composer('*', ...)`                 |

---