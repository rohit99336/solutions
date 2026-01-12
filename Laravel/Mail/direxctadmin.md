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

>**DirectAdmin email को Laravel में send + receive दोनों के लिए configure कर सकते हैं**
> 👉 Send = SMTP
> 👉 Receive = IMAP (Webklex package)

---


**IMAP** वाला पैकेज इस्तेमाल कर रहे हैं, उसके नए वर्जन (v6+) में पब्लिश करने का कमांड थोड़ा बदल गया है। लातवेल 12 में यह दिक्कत अक्सर इसलिए आती है क्योंकि हम पुराना कमांड चला रहे होते हैं।

आपके टर्मिनल लॉग्स दिखा रहे हैं कि पैकेज सफलतापूर्वक इंस्टॉल हो गया है (`webklex/laravel-imap .... DONE`), लेकिन वह पब्लिश इसलिए नहीं हो रहा क्योंकि उसे वह **Provider** मिल ही नहीं रहा जो आप लिख रहे हैं।

इसे ठीक करने के लिए नीचे दिए गए कदम उठाएं:

### १. सही पब्लिश कमांड चलाएं

नए वर्जन में प्रोवाइडर का नाम बदल गया है। आप इसकी जगह सिर्फ **Tag** का इस्तेमाल करें, यह ज्यादा बेहतर तरीके से काम करता है:

```bash
php artisan vendor:publish --provider="Webklex\IMAP\Providers\LaravelServiceProvider"

```

अगर ऊपर वाला काम न करे, तो यह वाला चलाएं (यह सबसे सटीक है):

```bash
php artisan vendor:publish --tag=imap

```

### २. अगर फिर भी "No publishable resources" आए

अगर ऊपर दिए गए कमांड से भी कुछ न हो, तो इसका मतलब है कि लातवेल ने नए पैकेज को ठीक से रजिस्टर नहीं किया है। इसे ठीक करने के लिए ये कमांड्स एक-एक करके चलाएं:

```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload

```

इसके बाद दोबारा `php artisan vendor:publish --tag=imap` चलाएं।

---

### ३. एक ज़रूरी सुधार (लॉग्स के अनुसार)

आपके टर्मिनल में एक एरर दिख रहा है जो आपके प्रोजेक्ट को धीमा कर सकता है या आगे जाकर दिक्कत देगा:

> `Class App\Imports\HotelImport located in ... does not comply with psr-4 autoloading standard`

**इसका समाधान:** आपके `app/Imports` फोल्डर में `HotelImport-v1.php` और `HotelImport-v2.php` नाम की फाइलें हैं। लातवेल की फाइलों के नाम और उनके अंदर लिखी `class` का नाम **बिल्कुल एक जैसा** होना चाहिए।

* या तो उन फाइलों को हटा दें।
* या फिर फाइल का नाम बदलकर सिर्फ `HotelImport.php` रखें (अगर वह सही वर्जन है)।

---

### ४. कॉन्फ़िगरेशन चेक करें

जब पब्लिश सफल हो जाएगा, तो आपके प्रोजेक्ट के `config` फोल्डर में `imap.php` नाम की फाइल बन जाएगी। उसमें आपको अपने मेल सर्वर की जानकारी (जैसे Gmail या Hostinger) भरनी होगी।