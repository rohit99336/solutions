Mailpit एक lightweight Email Testing Tool है जिसका उपयोग local development environment में email test करने के लिए किया जाता है।
जब आप Laravel application में mail भेजते हैं, तो असली email user तक जाने के बजाय Mailpit उसे capture कर लेता है और browser UI में दिखाता है।

इससे आप safely email templates, OTP, notifications, attachments आदि test कर सकते हैं बिना real mail भेजे।

---

# Mailpit के फायदे

* Local machine पर fast चलता है
* SMTP server की तरह काम करता है
* सभी sent emails browser में दिखाता है
* HTML email preview मिलता है
* Attachments भी देख सकते हैं
* Laravel के साथ बहुत आसानी से integrate हो जाता है
* Mailhog से ज्यादा modern UI

---

# Laravel में Mailpit कैसे Use करें

## Method 1 — Laravel Herd / Sail Users

यदि आप Laravel Herd या Sail use कर रहे हैं तो कई बार Mailpit पहले से install रहता है।

Browser में यह URL खोलकर check करें:

```bash
http://127.0.0.1:8025
```

यदि UI खुल जाए तो Mailpit already running है।

---

# Method 2 — Manual Install (Recommended)

## Step 1: Mailpit Download करें

Official website:

```bash
https://mailpit.axllent.org/
```

या GitHub releases से download करें।

Linux के लिए:

```bash
wget https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64.tar.gz
```

Extract करें:

```bash
tar -xzf mailpit-linux-amd64.tar.gz
```

Permission दें:

```bash
chmod +x mailpit
```

---

# Step 2: Mailpit Start करें

```bash
./mailpit
```

अब यह दो ports पर चलेगा:

| Service | Port |
| ------- | ---- |
| SMTP    | 1025 |
| Web UI  | 8025 |

---

# Step 3: Browser में Open करें

```bash
http://localhost:8025
```

अब यहाँ सभी captured emails दिखाई देंगे।

---

# Step 4: Laravel `.env` Configure करें

Laravel project की `.env` file में:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="test@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

# Step 5: Config Cache Clear करें

```bash
php artisan config:clear
```

---

# Step 6: Test Email भेजें

## Route Example

`routes/web.php`

```php
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {

    Mail::raw('यह एक टेस्ट मेल है', function ($message) {
        $message->to('demo@example.com')
                ->subject('Laravel Mailpit Test');
    });

    return 'Mail Sent';
});
```

---

# Step 7: Browser में Route Open करें

```bash
http://127.0.0.1:8000/test-mail
```

अब Mailpit UI में email दिखाई देगा।

---

# Laravel Mailable के साथ Use

यदि आप `php artisan make:mail` use करते हैं तब भी Mailpit perfectly काम करता है।

Example:

```bash
php artisan make:mail WelcomeMail
```

फिर:

```php
Mail::to('test@gmail.com')->send(new WelcomeMail());
```

Email Mailpit UI में capture हो जाएगा।

---

# Docker में Mailpit Use करना

यदि Docker use कर रहे हैं:

```yaml
mailpit:
    image: axllent/mailpit
    ports:
        - 1025:1025
        - 8025:8025
```

---

# Mailpit vs Real SMTP

| Feature             | Mailpit | Gmail SMTP |
| ------------------- | ------- | ---------- |
| Real Email भेजता है | ❌       | ✅          |
| Local Testing       | ✅       | ❌          |
| Fast                | ✅       | ❌          |
| Safe Testing        | ✅       | ❌          |
| Internet Needed     | ❌       | ✅          |

---

# Production में Use न करें

Mailpit केवल development/testing के लिए है।

Production में use करें:

* Google Gmail SMTP
* Mailgun
* Amazon SES
* Brevo
* Postmark

---

# Important Tips

## Auto Start

Linux में background में चलाने के लिए:

```bash
nohup ./mailpit &
```

---

## Port Change

```bash
./mailpit --smtp 2525 --ui 8080
```

---

# Laravel Sail में Built-in Support

यदि आप Laravel Sail use करते हैं:

```bash
./vendor/bin/sail up
```

फिर:

```bash
http://localhost:8025
```

Mailpit automatically available होता है।

---