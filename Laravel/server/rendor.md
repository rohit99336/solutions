बहुत बढ़िया! ✅ अब मैं आपको **Render.com पर Laravel Project free में deploy करने के पूरे step-by-step process** बता रहा हूँ — बिना किसी **credit/debit card** के।

---

## 🛠️ Prerequisites

1. ✅ GitHub account होना चाहिए
2. ✅ आपका Laravel project GitHub पर uploaded होना चाहिए (private/public दोनों चलेगा)
3. ✅ `.env.example` file होना चाहिए (credentials छुपाए जाएं)
4. ✅ SQLite या external DB (Render free tier में MySQL hosted करना मुश्किल है)

---

## 🧭 Step-by-Step: Laravel Deploy on Render (Free Tier)

---

### 🟢 Step 1: GitHub par project push karein

अगर नहीं किया है तो command:

```bash
git init
git remote add origin https://github.com/your-username/your-laravel-project.git
git add .
git commit -m "initial commit"
git push -u origin master
```

---

### 🟢 Step 2: Render par account banayein

1. Visit [https://render.com](https://render.com)
2. "Sign Up" पर क्लिक करें
3. GitHub से login करें (authorize जरूर करें)
4. Dashboard खुल जाएगा

---

### 🟢 Step 3: New Web Service create करें

1. Click on **“New +” → “Web Service”**
2. Select your **Laravel GitHub repo**
3. Fill details:

| Setting          | Value                                                                     |
| ---------------- | ------------------------------------------------------------------------- |
| Name             | laravel-app (or anything)                                                 |
| Environment      | PHP                                                                       |
| Build Command    | `composer install && php artisan config:cache && php artisan route:cache` |
| Start Command    | `php artisan serve --host 0.0.0.0 --port 10000`                           |
| Environment Type | `Web Service`                                                             |
| Region           | Closest to your users                                                     |

✅ Continue पर click करें और deploy शुरू हो जाएगा

---

### 🟢 Step 4: SQLite use करें (MySQL की बजाय)

1. Laravel में `.env.example` को ऐसा set करें:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/tmp/database.sqlite
```

2. Terminal में:

```bash
touch database/database.sqlite
```

3. `config/database.php` में SQLite config ensure करें:

```php
'sqlite' => [
    'driver' => 'sqlite',
    'url' => env('DATABASE_URL'),
    'database' => env('DB_DATABASE', database_path('database.sqlite')),
    'prefix' => '',
    'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
],
```

---

### 🟢 Step 5: Static storage + cache folders create करें

Laravel को run करने के लिए ये folders जरूरी हैं:

**Render Dashboard → Environment → Add Environment Variables**

| Key            | Value                                     |
| -------------- | ----------------------------------------- |
| APP\_KEY       | `base64:` से generate करें (नीचे दिया है) |
| APP\_ENV       | production                                |
| APP\_DEBUG     | false                                     |
| DB\_CONNECTION | sqlite                                    |
| DB\_DATABASE   | /tmp/database.sqlite                      |

✅ APP\_KEY generate करें:

```bash
php artisan key:generate --show
```

---

### 🟢 Step 6: Deploy और Test करें

* जब तक “Build Successful” ना दिखे, wait करें
* URL मिल जाएगा → Click करें
* Laravel welcome page या आपकी app खुलनी चाहिए

---

## 💡 Optional Tips

* Background job (queue) के लिए “Background Worker” service बना सकते हैं
* Cron job के लिए Render cron supported है
* File storage के लिए AWS S3 या Cloudinary integrate करें

---

## 🤔 Agar error aaye to:

1. Log में देखें (Deploy → View Logs)
2. `.env` में values check करें
3. Database migrate करें:

   * Add “Shell” tab in dashboard → Run:

     ```bash
     php artisan migrate
     ```

---

## ✅ Conclusion

Render.com आपको free में Laravel host करने देता है, **कोई card नहीं चाहिए**, बस SQLite DB use करना पड़ेगा या external DB लगाना होगा.

---