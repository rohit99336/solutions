**आप DirectAdmin का mail अपने Laravel में भेजने (send) के साथ-साथ प्राप्त (receive) करने के लिए भी configure कर सकते हैं**।
Laravel में ये काम **SMTP (send)** + **IMAP/POP3 (receive)** के combination से होता है।

मैं इसे **सरल + practical तरीके** से समझा रहा हूँ 👇

---

## 🔹 Overall Concept (एक नज़र में)

| काम                   | Protocol    | Laravel में कैसे           |
| --------------------- | ----------- | -------------------------- |
| 📤 Email भेजना        | SMTP        | Laravel Mail               |
| 📥 Email प्राप्त करना | IMAP / POP3 | Webklex IMAP               |
| 🧾 Mail store करना    | Database    | Custom tables              |
| 🔁 Auto fetch         | Scheduler   | `php artisan schedule:run` |

---

## 1️⃣ DirectAdmin से Email भेजना (SMTP)

यह आप शायद पहले से कर रहे होंगे 👇

### `.env` (DirectAdmin SMTP)

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=info@yourdomain.com
MAIL_PASSWORD=EMAIL_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@yourdomain.com
MAIL_FROM_NAME="Your App"
```

👉 यह **Laravel → DirectAdmin SMTP** से mail भेजेगा

---

## 2️⃣ DirectAdmin से Email प्राप्त करना (IMAP)

Laravel core में **email receive feature नहीं होता**,
इसके लिए package use करना पड़ता है।

### ✅ Best Package

```bash
composer require webklex/laravel-imap
```

Publish config:

```bash
php artisan vendor:publish --tag=laravel-imap
```

---

## 3️⃣ IMAP Configuration (DirectAdmin)

### `config/imap.php`

```php
'accounts' => [
    'default' => [
        'host'          => 'mail.yourdomain.com',
        'port'          => 993,
        'protocol'      => 'imap',
        'encryption'    => 'ssl',
        'validate_cert' => true,
        'username'      => 'info@yourdomain.com',
        'password'      => 'EMAIL_PASSWORD',
    ],
],
```

👉 DirectAdmin में IMAP आमतौर पर:

* Port: **993 (SSL)**
* Host: **mail.yourdomain.com**

---

## 4️⃣ Mail Receive करने का Example Code

```php
use Webklex\IMAP\Facades\Client;

$client = Client::account('default');
$client->connect();

$inbox = $client->getFolder('INBOX');

$messages = $inbox->messages()->unseen()->get();

foreach ($messages as $message) {
    $from = $message->getFrom()[0]->mail;
    $subject = $message->getSubject();
    $body = $message->getHTMLBody();

    // DB में save करें
    ReceivedMail::create([
        'from_email' => $from,
        'subject' => $subject,
        'body' => $body,
    ]);

    // mail को read mark करें
    $message->setFlag('Seen');
}
```

---

## 5️⃣ Auto Receive (Scheduler से)

### Command बनाएँ

```bash
php artisan make:command FetchEmails
```

### `app/Console/Commands/FetchEmails.php`

```php
protected $signature = 'mail:fetch';

public function handle()
{
    $client = Client::account('default');
    $client->connect();

    $inbox = $client->getFolder('INBOX');

    foreach ($inbox->messages()->unseen()->get() as $message) {
        // save logic
        $message->setFlag('Seen');
    }
}
```

### Scheduler

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('mail:fetch')->everyFiveMinutes();
}
```

👉 अब **DirectAdmin inbox → Laravel DB** automatic sync होगा

---

## 6️⃣ Laravel में Use-Cases (Real Life)

आप ये कर सकते हैं 👇

* ✔️ Support Ticket system
* ✔️ Reply via Email (two-way communication)
* ✔️ Invoice / Reply tracking
* ✔️ Contact-form reply capture
* ✔️ WhatsApp + Email combined inbox

---

## 🔐 Security Tips (बहुत ज़रूरी)

* ❌ `.env` public न हो
* ❌ IMAP command को web route से न चलाएँ
* ✅ Scheduler + cron से run करें
* ✅ अलग mailbox use करें (support@, noreply@)

---

## 🧠 Summary (एक लाइन में)

> **हाँ**, आप **DirectAdmin email को Laravel में send + receive दोनों के लिए configure कर सकते हैं**
> 👉 Send = SMTP
> 👉 Receive = IMAP (Webklex package)

---

अगर आप चाहें तो मैं अगला step बना सकता हूँ:

* ✅ **Complete Email Inbox UI (Gmail-like)**
* ✅ **Auto reply system**
* ✅ **Ticket system with email threading**
* ✅ **Multiple mailbox support**

बस बताइए 😊
