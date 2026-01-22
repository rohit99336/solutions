**Firebase Service Account JSON** फाइल को **Laravel 12** में सेटअप करना सबसे महत्वपूर्ण स्टेप है, क्योंकि इसी फाइल के जरिए आपका बैकएंड (PHP) सुरक्षित रूप से Google के सर्वर से बात कर पाता है।

इसे सेटअप करने का प्रोफेशनल तरीका नीचे दिया गया है:

### स्टेप 1: JSON फाइल को सुरक्षित जगह रखें

1. जो फाइल आपने Firebase से डाउनलोड की है, उसका नाम बदलकर `firebase_service_accounts.json` रख दें।
2. इसे अपने प्रोजेक्ट के **`storage/app/`** फोल्डर के अंदर एक नया फोल्डर **`firebase`** बनाकर वहां डाल दें।
* पाथ: `storage/app/firebase/firebase_service_accounts.json`
* **नोट:** इसे कभी भी `public` फोल्डर में न रखें, वरना कोई भी इसे डाउनलोड कर सकता है।



---

### स्टेप 2: .env फाइल में पाथ सेव करें

अपनी `.env` फाइल में उस फाइल का पाथ लिख दें ताकि कोड में इसे आसानी से बदला जा सके:

```env
FCM_SERVICE_ACCOUNT_PATH=storage/app/firebase/firebase_service_accounts.json

```

---

### स्टेप 3: Laravel Notification Channel इंस्टॉल करें

Laravel 12 में FCM (V1 API) के जरिए नोटिफिकेशन भेजने के लिए सबसे ज्यादा इस्तेमाल होने वाला पैकेज इंस्टॉल करें:

```bash
composer require laravel-notification-channels/fcm

```

---

### स्टेप 4: कॉन्फ़िगरेशन सेटअप

पैकेज इंस्टॉल होने के बाद, आपको उसे बताना होगा कि आपकी JSON फाइल कहाँ है। वैसे तो यह पैकेज डिफ़ॉल्ट रूप से चीज़ें उठा लेता है, लेकिन इसे पक्का करने के लिए आप `config/services.php` में यह जोड़ सकते हैं:

```php
'fcm' => [
    'service_account' => base_path(env('FCM_SERVICE_ACCOUNT_PATH')),
],

```

---

### स्टेप 5: नोटिफिकेशन क्लास तैयार करें

अब एक नोटिफिकेशन क्लास बनाएं: `php artisan make:notification SendPushNotification`

उस फाइल (`app/Notifications/SendPushNotification.php`) में यह कोड लिखें:

```php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class SendPushNotification extends Notification
{
    public function via($notifiable)
    {
        return [FcmChannel::class]; // यह FCM चैनल का उपयोग करेगा
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'नमस्ते!',
            body: 'यह आपका पहला पुश नोटिफिकेशन है।',
            image: asset('logo.png')
        )));
    }
}

```

---

### स्टेप 6: यूज़र मॉडल (User Model) में सुधार

अपने `User.php` मॉडल में यह फंक्शन जोड़ें ताकि Laravel को पता चले कि नोटिफिकेशन किस टोकन पर भेजना है:

```php
public function routeNotificationForFcm()
{
    return $this->fcm_token; // जो कॉलम हमने डेटाबेस में बनाया था
}

```

---

### स्टेप 7: नोटिफिकेशन भेजें (Test करें)

अब आप अपने किसी भी कंट्रोलर या रूट से इसे टेस्ट कर सकते हैं:

```php
use App\Models\User;
use App\Notifications\SendPushNotification;

Route::get('/send-test-notification', function () {
    $user = User::find(1); // किसी एक यूज़र को चुनें
    $user->notify(new SendPushNotification());
    
    return "नोटिफिकेशन भेज दिया गया!";
});

```

### ज़रूरी सलाह (Expert Tip):

चूंकि आप **Queue (क्यू)** के बारे में पहले पूछ चुके हैं, तो याद रखें कि पुश नोटिफिकेशन भेजने में समय लगता है। इसलिए अपनी नोटिफिकेशन क्लास में **`implements ShouldQueue`** ज़रूर लिखें। इससे आपका सर्वर धीमा नहीं होगा और नोटिफिकेशन बैकग्राउंड में चले जाएंगे।

```php
class SendPushNotification extends Notification implements ShouldQueue 
{ 
    use Queueable; 
    // ... बाकी कोड
}

```

क्या अब आप इसे अपने प्रोजेक्ट में टेस्ट करने के लिए तैयार हैं या टोकन सेव करने वाले कंट्रोलर में कोई मदद चाहिए?