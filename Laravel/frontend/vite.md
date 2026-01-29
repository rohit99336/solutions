ठीक है 🙂
**Eternal HTML theme** (या कोई भी HTML theme) को **Laravel + Vite** में **CSS/JS** के साथ सही तरीके से कैसे integrate करते हैं।

---

## 1️⃣ Eternal HTML Theme को समझना

जब आप Eternal theme डाउनलोड करते हैं, आमतौर पर इसमें ये folders होते हैं:

* `assets/css` → सभी CSS files
* `assets/js` → सभी JS files
* `assets/images` → images
* `index.html`, `about.html` आदि

Laravel में हमें:

* **CSS और JS → Vite से manage**
* **Images → public folder में**
* **HTML → Blade files में convert**

---

## 2️⃣ Laravel Project में Theme Files को कहाँ रखें

### 📁 CSS और JS

Laravel + Vite में best practice:

```
resources/
 └── css/
 └── js/
```

👉 Eternal theme से:

* सभी CSS files → `resources/css/`
* सभी JS files → `resources/js/`

उदाहरण:

```
resources/css/style.css
resources/css/bootstrap.min.css

resources/js/main.js
resources/js/bootstrap.bundle.min.js
```

---

### 📁 Images और Fonts

Images और fonts को **public** में रखें:

```
public/assets/images
public/assets/fonts
```

> Vite images को bundle नहीं करता, इसलिए images हमेशा `public` में बेहतर रहते हैं।

---

## 3️⃣ CSS को Vite में Add करना

### `resources/css/app.css`

इस file में theme की सभी CSS import करें:

```css
@import "./bootstrap.min.css";
@import "./style.css";
@import "./responsive.css";
```

👉 अगर बहुत सारी CSS हैं, तो सबको यहीं import करें।

---

## 4️⃣ JS को Vite में Add करना

### `resources/js/app.js`

इस file में theme की JS files import करें:

```js
import './bootstrap.bundle.min.js';
import './main.js';
```

⚠️ ध्यान रखें:

* jQuery based theme है तो पहले jQuery import करें
* Pure JS / Bootstrap 5 है तो jQuery की जरूरत नहीं

---

## 5️⃣ `vite.config.js` Check करें

Laravel default config आमतौर पर ठीक होता है:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
```

---

## 6️⃣ Blade File में Vite Load करना

### `resources/views/layouts/app.blade.php`

`<head>` में:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

❌ CSS/JS को direct `<link>` या `<script>` से include न करें
✅ हमेशा Vite का use करें

---

## 7️⃣ HTML को Blade में Convert करना

Eternal theme की `index.html` को:

```
resources/views/home.blade.php
```

में paste करें।

फिर:

* `.html` हटाएँ
* Laravel syntax use करें

### Example:

```html
<img src="assets/images/logo.png">
```

👇 इसे बदलें:

```blade
<img src="{{ asset('assets/images/logo.png') }}">
```

---

## 8️⃣ Layout और Sections बनाना (Best Practice)

### `layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

@include('partials.header')

@yield('content')

@include('partials.footer')

</body>
</html>
```

### `home.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Eternal theme ka body content -->
@endsection
```

---

## 9️⃣ Vite Run करना (बहुत जरूरी)

Terminal में:

```bash
npm install
npm run dev
```

Production के लिए:

```bash
npm run build
```

---

## 🔟 Common Errors और Solutions

### ❌ CSS / JS load नहीं हो रही

✔ `npm run dev` चालू है या नहीं check करें
✔ `@vite` blade में लगा है या नहीं

---

### ❌ Images नहीं दिख रही

✔ Images `public/assets` में हैं
✔ `asset()` helper use किया गया है

---

## ✅ Final Flow Summary

1. Theme से CSS/JS → `resources`
2. Images → `public`
3. CSS/JS → `app.css` & `app.js` में import
4. Blade में `@vite`
5. HTML → Blade
6. `npm run dev`

---