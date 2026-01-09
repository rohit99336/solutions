**Hostinger का mail भी Laravel में send + receive दोनों के लिए setup किया जा सकता है**।

---
## 🔹 High-Level Concept (Same as before)

| काम                  | Protocol         | Laravel में       |
| -------------------- | ---------------- | ----------------- |
| 📤 Mail भेजना        | SMTP             | Laravel Mail      |
| 📥 Mail प्राप्त करना | IMAP / POP3      | IMAP Package      |
| 🔁 Auto fetch        | Cron / Scheduler | Laravel Scheduler |

👉 Hostinguer = **SMTP + IMAP support देता है**, इसलिए full two-way email possible है।

---

## 1️⃣ Hostinguer से Email भेजना (SMTP)

### `.env` Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=info@yourdomain.com
MAIL_PASSWORD=EMAIL_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@yourdomain.com
MAIL_FROM_NAME="Your App"
```

✔️ यह setup **Laravel → Hostinger SMTP** से mail भेजेगा।

---

## 2️⃣ Hostinguer से Email प्राप्त करना (IMAP)

Laravel core में receive feature नहीं होता, इसलिए IMAP package जरूरी है।

### Package Install

```bash
composer require webklex/laravel-imap
```

```bash
php artisan vendor:publish --tag=laravel-imap
```

---

## 3️⃣ Hostinguer IMAP Settings

### `config/imap.php`

```php
'accounts' => [
    'default' => [
        'host'          => 'imap.hostinger.com',
        'port'          => 993,
        'protocol'      => 'imap',
        'encryption'    => 'ssl',
        'validate_cert' => true,
        'username'      => 'info@yourdomain.com',
        'password'      => 'EMAIL_PASSWORD',
    ],
],
```

📌 Hostinguer default ports:

* **SMTP** → 587 (TLS)
* **IMAP** → 993 (SSL)

---

## 4️⃣ Laravel में Mail Receive Example

```php
use Webklex\IMAP\Facades\Client;

$client = Client::account('default');
$client->connect();

$inbox = $client->getFolder('INBOX');

$messages = $inbox->messages()->unseen()->get();

foreach ($messages as $mail) {
    $from    = $mail->getFrom()[0]->mail;
    $subject = $mail->getSubject();
    $body    = $mail->getHTMLBody();

    // DB में save करें
    ReceivedMail::create([
        'from_email' => $from,
        'subject'    => $subject,
        'body'       => $body,
    ]);

    // Read mark करें
    $mail->setFlag('Seen');
}
```

---

## 5️⃣ Auto-Receive (Cron / Scheduler)

### Artisan Command

```bash
php artisan make:command FetchHostingerMail
```

```php
protected $signature = 'mail:fetch-hostinger';

public function handle()
{
    $client = Client::account('default');
    $client->connect();

    $inbox = $client->getFolder('INBOX');

    foreach ($inbox->messages()->unseen()->get() as $mail) {
        // Save logic
        $mail->setFlag('Seen');
    }
}
```

### Scheduler

```php
$schedule->command('mail:fetch-hostinger')->everyFiveMinutes();
```

---

## 6️⃣ Hostinguer + Laravel से क्या-क्या Possible है?

* ✔️ Support Ticket via Email
* ✔️ Reply directly from Laravel (two-way)
* ✔️ Contact-form replies capture
* ✔️ Invoice / Order mail tracking
* ✔️ WhatsApp + Email combined inbox

---

## 🔐 Security Best Practices

* ❌ IMAP command को web route से मत चलाइए
* ✅ Cron + scheduler use करें
* ✅ Separate mailbox रखें (support@, noreply@)
* ❌ Root / main email avoid करें

---

## 🧠 Final Answer (एक लाइन में)

> Hostinguer mail को भी **DirectAdmin की तरह Laravel में send + receive दोनों के लिए 100% setup किया जा सकता है**
> 👉 Send = SMTP
> 👉 Receive = IMAP
