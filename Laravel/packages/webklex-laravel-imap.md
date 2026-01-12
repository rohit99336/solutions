**`webklex/laravel-imap`** एक बहुत ही पावरफुल पैकेज है जो आपके लातवेल प्रोजेक्ट को ईमेल पढ़ने, इनबॉक्स को स्कैन करने और अटैचमेंट्स डाउनलोड करने की ताकत देता है।

यहाँ इस पैकेज का इंस्टॉलेशन से लेकर इस्तेमाल तक का पूरा विवरण शुद्ध हिंदी (देवनागरी) में दिया गया है:

---

### १. इंस्टॉलेशन (Installation)

आपने इसे इंस्टॉल तो कर लिया है, लेकिन कॉन्फ़िगरेशन के लिए ये स्टेप्स ज़रूरी हैं:

1. **पैकेज पब्लिश करें:**
```bash
php artisan vendor:publish --provider="Webklex\LaravelIMAP\Providers\LaravelServiceProvider"

```


*इससे आपके `config/` फोल्डर में `imap.php` फाइल बन जाएगी।*
2. **PHP एक्सटेंशन:**
सुनिश्चित करें कि आपके सिस्टम में `php-imap`, `php-mbstring` और `php-mcrypt` एक्सटेंशन इंस्टॉल हैं।

---

### २. मुख्य फीचर्स (Key Features)

* **IMAP, POP3 और NNTP:** यह सभी प्रमुख प्रोटोकॉल को सपोर्ट करता है।
* **Attachments:** आप ईमेल के साथ आई फाइलों को आसानी से डाउनलोड कर सकते हैं।
* **Search:** आप ईमेल को 'Subject', 'From', 'Unseen' (बिना पढ़ी हुई) आदि के आधार पर ढूंढ सकते हैं।
* **Flags:** आप मेल को 'Read', 'Important' या 'Deleted' मार्क कर सकते हैं।
* **Multi-Account:** एक साथ कई ईमेल अकाउंट्स को हैंडल कर सकते हैं।

---

### ३. मुख्य मेथड्स और इस्तेमाल (Core Methods)

ईमेल पढ़ने के लिए सबसे पहले आपको क्लाइंट कनेक्ट करना होगा:

#### क. सर्वर से कनेक्ट करना

```php
use Webklex\IMAP\Facades\Client;

$client = Client::account('default'); // 'default' config/imap.php से आता है
$client->connect();

```

#### ख. फोल्डर्स और ईमेल्स प्राप्त करना

```php
// इनबॉक्स फोल्डर चुनें
$folder = $client->getFolder('INBOX');

// सभी ईमेल्स प्राप्त करें
$messages = $folder->messages()->all()->get();

foreach($messages as $message) {
    echo $message->getSubject();      // विषय (Subject)
    echo $message->getHTMLBody();     // ईमेल की बॉडी (HTML)
    echo $message->getFrom()[0]->mail; // भेजने वाले का ईमेल
}

```

#### ग. बिना पढ़ी हुई (Unseen) ईमेल ढूंढना

यह सबसे ज्यादा इस्तेमाल होने वाला मेथड है:

```php
$messages = $folder->query()->unseen()->get();

```

#### घ. अटैचमेंट डाउनलोड करना (Attachments)

```php
foreach($messages as $message) {
    $attachments = $message->getAttachments();
    
    foreach($attachments as $attachment) {
        // फाइल को 'storage/app/public' में सेव करना
        $attachment->save(storage_path('app/public/'));
    }
}

```

---

### ४. कॉन्फ़िगरेशन (`config/imap.php`)

अपनी `.env` फाइल में ये सेटिंग्स ज़रूर डालें:

```env
IMAP_HOST=imap.gmail.com
IMAP_PORT=993
IMAP_ENCRYPTION=ssl
IMAP_VALIDATE_CERT=true
IMAP_USERNAME=your-email@gmail.com
IMAP_PASSWORD=your-app-password
IMAP_DEFAULT_ACCOUNT=default

```

---

### ५. ज़रूरी सावधानियां (Expert Tips)

1. **Gmail App Password:** अगर आप Gmail यूज़ कर रहे हैं, तो अपना सामान्य पासवर्ड न डालें। Google Account में जाकर **"App Password"** जेनरेट करें, तभी कनेक्शन होगा।
2. **Performance:** `all()->get()` का उपयोग बड़े इनबॉक्स पर न करें, यह सर्वर को धीमा कर देगा। हमेशा `limit()` या `query()` का उपयोग करें।
3. **Error Handling:** हमेशा अपने कोड को `try-catch` ब्लॉक में रखें क्योंकि मेल सर्वर कभी भी कनेक्शन काट सकते हैं।

===============================================================================

**Production-Ready Controller** का कोड । इसमें मैंने **Performance** का खास ध्यान रखा है ताकि आपका सर्वर क्रैश न हो और केवल नए (Unseen) ईमेल ही प्रोसेस हों।

यहाँ `limit()` और `query()` के साथ बेहतर तरीका दिया गया है:

### १. ईमेल प्रोसेसिंग कंट्रोलर (EmailController.php)

इस कोड को आप `app/Http/Controllers/Admin/EmailController.php` में रख सकते हैं:

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Webklex\IMAP\Facades\Client;
use App\Models\Participant;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmailController extends Controller
{
    public function syncEmails()
    {
        try {
            // १. क्लाइंट कनेक्ट करें
            $client = Client::account('default');
            $client->connect();

            // २. इनबॉक्स फोल्डर प्राप्त करें
            $folder = $client->getFolder('INBOX');

            // ३. PERFORMANCE TIP: 'all()->get()' की जगह 'query()' और 'limit()' का उपयोग
            // हम यहाँ सिर्फ वो ईमेल ले रहे हैं जो 'Unseen' (अनपढ़े) हैं और ताज़ा १० ईमेल ही उठा रहे हैं
            $messages = $folder->query()
                ->unseen()           // केवल बिना पढ़े ईमेल
                ->limit(10)          // एक बार में केवल १० ईमेल (सर्वर लोड कम करने के लिए)
                ->setFetchOrderDesc() // नए ईमेल सबसे पहले
                ->get();

            $count = 0;

            foreach ($messages as $message) {
                // ४. ईमेल से डेटा निकालना
                $emailAddress = $message->getFrom()[0]->mail;
                $subject = $message->getSubject();
                $body = $message->getTextBody() ?: $message->getHTMLBody();

                // ५. डेटाबेस में सेव करने का लॉजिक
                // check if already exists to avoid duplicates
                $exists = Participant::where('email', $emailAddress)->exists();

                if (!$exists) {
                    Participant::create([
                        'name'  => $message->getFrom()[0]->personal ?: 'Email User',
                        'email' => $emailAddress,
                        'phone' => '0000000000', // ईमेल में फोन नहीं होता तो डिफॉल्ट डालें
                        'created_at' => Carbon::parse($message->getDate()),
                    ]);
                    $count++;
                }

                // ६. ईमेल को 'Read' (पढ़ा हुआ) मार्क करें ताकि अगली बार ये दोबारा न आये
                $message->setFlag('Seen');
            }

            return back()->with('success', "$count नए पार्टिसिपेंट्स सफलतापूर्वक जोड़े गए।");

        } catch (\Exception $e) {
            Log::error("IMAP Error: " . $e->getMessage());
            return back()->with('error', "ईमेल सिंक करने में समस्या आई: " . $e->getMessage());
        }
    }
}

```

### Attachments :-

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Webklex\IMAP\Facades\Client;
use App\Models\Participant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmailController extends Controller
{
    public function syncEmails()
    {
        try {
            $client = Client::account('default');
            $client->connect();
            $folder = $client->getFolder('INBOX');

            // केवल अनपढ़े और ताज़ा १० ईमेल
            $messages = $folder->query()->unseen()->limit(10)->setFetchOrderDesc()->get();

            $count = 0;
            foreach ($messages as $message) {
                $emailAddress = $message->getFrom()[0]->mail;
                
                // १. डेटाबेस में रिकॉर्ड बनाना या अपडेट करना
                $participant = Participant::updateOrCreate(
                    ['email' => $emailAddress],
                    [
                        'name' => $message->getFrom()[0]->personal ?: 'Email User',
                        'created_at' => Carbon::parse($message->getDate()),
                    ]
                );

                // २. अटैचमेंट्स (Attachments) को संभालना
                if ($message->hasAttachments()) {
                    $attachments = $message->getAttachments();

                    foreach ($attachments as $attachment) {
                        // फाइल का नाम सुरक्षित बनाना
                        $filename = time() . '_' . $attachment->getName();
                        
                        // फाइल को 'storage/app/public/attachments' में सेव करना
                        // नोट: पक्का करें कि आपने 'php artisan storage:link' चलाया हुआ है
                        $path = 'public/attachments/' . $participant->id . '/';
                        
                        // अटैचमेंट की सामग्री को स्टोर करना
                        Storage::put($path . $filename, $attachment->getContent());

                        // यहाँ आप चाहें तो डेटाबेस के किसी 'file_path' कॉलम में इसे सेव कर सकते हैं
                        // $participant->update(['document' => $path . $filename]);
                    }
                }

                // ३. ईमेल को 'Seen' मार्क करें ताकि अगली बार न आए
                $message->setFlag('Seen');
                $count++;
            }

            return back()->with('success', "$count ईमेल और उनके दस्तावेज़ सफलतापूर्वक प्रोसेस किए गए।");

        } catch (\Exception $e) {
            Log::error("IMAP Attachment Error: " . $e->getMessage());
            return back()->with('error', "समस्या: " . $e->getMessage());
        }
    }
}
```

---

### इस कोड की मुख्य बातें (Key Features):

१. **`hasAttachments()`**: यह चेक करता है कि ईमेल में कोई फाइल है भी या नहीं, जिससे फालतू का कोड नहीं चलता।
२. **`getContent()`**: यह अटैचमेंट का बाइनरी डेटा (Binary Data) निकालता है जिसे हम `Storage::put()` की मदद से किसी भी फोल्डर में सेव कर सकते हैं।
३. **यूनिक फाइल नेम**: `time() . '_' . $attachment->getName()` का उपयोग किया है ताकि अगर दो लोग एक ही नाम की फाइल (जैसे `image.jpg`) भेजें, तो फाइलें आपस में ओवरराइट (Overwrite) न हों।
४. **फोल्डर स्ट्रक्चर**: मैंने `attachments/participant_id/` का स्ट्रक्चर इस्तेमाल किया है ताकि हर यूजर की फाइलें अलग-अलग और व्यवस्थित रहें।

---

### एक ज़रूरी टिप (Important Tip):

ईमेल के अटैचमेंट्स कभी-कभी बहुत भारी (MB में) होते हैं। अगर आप बहुत सारे अटैचमेंट्स एक साथ डाउनलोड कर रहे हैं, तो अपनी `php.ini` फाइल में **`memory_limit`** और **`max_execution_time`** को थोड़ा बढ़ा दें, नहीं तो आपका सर्वर 'Timeout' हो सकता है।

---

### २. परफॉरमेंस और सुरक्षा के मुख्य बिंदु (Key Points)

1. **`unseen()` का जादू:** यह केवल उन ईमेल को उठाएगा जिन्हें अभी तक पढ़ा नहीं गया है। इससे `all()->get()` की तरह हज़ारों ईमेल प्रोसेस करने की ज़रूरत नहीं पड़ती।
2. **`limit(10)`:** अगर आपके इनबॉक्स में ५०० अनपढ़े ईमेल हैं, तो यह कोड एक बार में सिर्फ १० को ही उठाएगा। इससे आपकी PHP की `memory_limit` खत्म नहीं होगी।
3. **`setFlag('Seen')`:** जैसे ही आपका कोड ईमेल पढ़ ले, उसे 'Seen' मार्क कर देना चाहिए। अगर आप ऐसा नहीं करेंगे, तो हर बार वही ईमेल दोबारा प्रोसेस होता रहेगा।
4. **`try-catch` और `Log`:** मेल सर्वर का कनेक्शन अक्सर टूट जाता है। `Log::error` से आपको बिना स्क्रीन एरर के पता चल जाएगा कि समस्या कहाँ है।

---

### ३. इसे ऑटोमैटिक कैसे चलाएं? (Automation)

आप नहीं चाहेंगे कि हर बार बटन दबाकर ईमेल सिंक करें। इसके लिए लातवेल के **Task Scheduling** का उपयोग करें।

`app/Console/Kernel.php` (या लातवेल ११/१२ में `routes/console.php`) में यह लिखें:

```php
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\Admin\EmailController;

// हर ५ मिनट में अपने आप ईमेल चेक करेगा
Schedule::call([EmailController::class, 'syncEmails'])->everyFiveMinutes();

```



