**frontend और admin दोनों के लिए अलग-अलग WebMaster data** pass करने का पूरा clean और professional तरीका दे रहा हूँ — ताकि हर जगह performance भी ठीक रहे और code भी organised रहे।

---

# ✅ **AppServiceProvider में दो अलग View Composers — एक Admin के लिए, एक Frontend के लिए**

### 📌 Code (Best Structure)

```php
use Illuminate\Support\Facades\View;
use App\Models\WebMaster;

public function boot(): void
{
    // 🔹 Common Webmaster Data
    $webmaster = WebMaster::first();

    /**
     * ----------------------------------------
     *  ADMIN VIEWS : resources/views/admin/*
     * ----------------------------------------
     */
    View::composer('admin.*', function ($view) use ($webmaster) {
        $view->with('webmaster', $webmaster);
    });

    /**
     * ----------------------------------------
     *  FRONTEND VIEWS : resources/views/frontend/*
     * ----------------------------------------
     */
    View::composer('frontend.*', function ($view) use ($webmaster) {
        $view->with('webmaster', $webmaster);
    });
}
```

---

# 📌 अब यह कैसे काम करेगा?

## **1️⃣ Admin Layout में**

```blade
{{-- resources/views/admin/layouts/app.blade.php --}}

<title>{{ $webmaster->hero_title ?? 'Admin Panel' }}</title>
```

Admin के अंदर कोई भी page:

```blade
<h3>{{ $webmaster->name }}</h3>
```

---

## **2️⃣ Frontend Layout में**

```blade
{{-- resources/views/frontend/layouts/app.blade.php --}}

<img src="{{ asset($webmaster->hero_image) }}" alt="Hero">
<p>{{ $webmaster->hero_subtitle }}</p>
```

---

# 🎉 Advantage

### ⭐ **1. Clean & Organised**

Frontend और Admin के लिए अलग composer — code समझने में आसान।

### ⭐ **2. Performance Optimized**

WebMaster सिर्फ एक बार load होगा (`$webmaster = WebMaster::first()`).

### ⭐ **3. Global Availability**

अब admin.* और frontend.* के सभी views में `$webmaster` ऑटोमेटिक available है।

### ⭐ **4. Layout को बार-बार modify करने की जरूरत नहीं**

---
