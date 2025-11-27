जरूर\! मैं आपके लिए एक छोटा सा, लेकिन स्पष्ट Laravel प्रोजेक्ट स्ट्रक्चर बना रहा हूँ जो SOLID सिद्धांतों (विशेष रूप से SRP, OCP, और DIP) का पालन करता है।

हम एक **"Article Publishing System"** बनाएंगे।

**परिदृश्य (Scenario):** जब एक आर्टिकल पब्लिश होता है, तो दो चीजें होनी चाहिए:

1.  आर्टिकल डेटाबेस में सेव होना चाहिए।
2.  एडमिन को एक नोटिफिकेशन भेजा जाना चाहिए (अभी के लिए हम सिर्फ Log में लिखेंगे, लेकिन बाद में इसे ईमेल या स्लैक में बदला जा सकता है)।

-----

### प्रोजेक्ट स्ट्रक्चर (Project Structure)

हम कोड को Controllers, Services, Repositories और Interfaces में बाँटेंगे।

### चरण 1: मॉडल और माइग्रेशन (Model & Migration)

सबसे पहले, आर्टिकल के लिए डेटाबेस टेबल बनाएँ।

```bash
php artisan make:model Article -m
```

**Migration File:** `database/migrations/xxxx_create_articles_table.php`

```php
public function up()
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->timestamps();
    });
}
```

**Model:** `app/Models/Article.php`

```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content'];
}
```

(माइग्रेशन रन करें: `php artisan migrate`)

-----

### चरण 2: इंटरफेसेस (Interfaces) - Contracts (DIP & OCP)

हम सीधे कंक्रीट क्लासेज पर निर्भर रहने के बजाय इंटरफेसेस (Contracts) बनाएंगे।

**Folder बनाएँ:** `app/Contracts`

1.  **नोटिफिकेशन के लिए इंटरफेस:** `app/Contracts/NotificationSender.php`

    ```php
    namespace App\Contracts;

    interface NotificationSender
    {
        public function send(string $message): void;
    }
    ```

2.  **डेटाबेस के लिए इंटरफेस (Repository Pattern):** `app/Contracts/ArticleRepository.php`

    ```php
    namespace App\Contracts;
    use App\Models\Article;

    interface ArticleRepository
    {
        public function create(array $data): Article;
    }
    ```

-----

### चरण 3: कार्यान्वयन (Implementations)

अब हम इन इंटरफेसेस को लागू करने वाली क्लासेज बनाएंगे।

1.  **नोटिफिकेशन का कार्यान्वयन (Log में भेजना):**
    **Folder बनाएँ:** `app/Services/Notifications`
    `app/Services/Notifications/LogNotificationSender.php`

    ```php
    namespace App\Services\Notifications;
    use App\Contracts\NotificationSender;
    use Illuminate\Support\Facades\Log;

    class LogNotificationSender implements NotificationSender
    {
        public function send(string $message): void
        {
            // यहाँ हम सिर्फ लॉग में लिख रहे हैं।
            // भविष्य में हम EmailSender या SlackSender बना सकते हैं बिना सर्विस कोड बदले। (OCP)
            Log::info("Notification Sent: " . $message);
        }
    }
    ```

2.  **डेटाबेस का कार्यान्वयन (Repository):**
    **Folder बनाएँ:** `app/Repositories`
    `app/Repositories/EloquentArticleRepository.php`

    ```php
    namespace App\Repositories;
    use App\Contracts\ArticleRepository;
    use App\Models\Article;

    class EloquentArticleRepository implements ArticleRepository
    {
        // डेटाबेस लॉजिक केवल यहाँ रहेगा (SRP)
        public function create(array $data): Article
        {
            return Article::create($data);
        }
    }
    ```

-----

### चरण 4: मुख्य सर्विस (The Main Service) - (SRP & DIP)

यह वह जगह है जहाँ मुख्य लॉजिक होगा। यह क्लास इंटरफेसेस पर निर्भर करेगी, न कि कंक्रीट क्लासेज पर।

**Folder बनाएँ:** `app/Services`
`app/Services/ArticleService.php`

```php
namespace App\Services;

use App\Contracts\ArticleRepository;
use App\Contracts\NotificationSender;
use App\Models\Article;

class ArticleService
{
    private $articleRepository;
    private $notificationSender;

    // Dependency Injection (DIP): हम Interface मांग रहे हैं
    public function __construct(
        ArticleRepository $articleRepository,
        NotificationSender $notificationSender
    ) {
        $this->articleRepository = $articleRepository;
        $this->notificationSender = $notificationSender;
    }

    public function publishArticle(array $data): Article
    {
        // 1. आर्टिकल सेव करें (Repository का उपयोग करके)
        $article = $this->articleRepository->create($data);

        // 2. नोटिफिकेशन भेजें (NotificationSender का उपयोग करके)
        $this->notificationSender->send("New article published: " . $article->title);

        return $article;
    }
}
```

-----

### चरण 5: सर्विस कंटेनर बाइंडिंग (Service Container Binding) - सबसे महत्वपूर्ण

Laravel को यह बताना होगा कि जब कोई Interface मांगे तो कौन सी Class देनी है।

`app/Providers/AppServiceProvider.php` खोलें और `register` मेथड में कोड जोड़ें:

```php
use App\Contracts\ArticleRepository;
use App\Repositories\EloquentArticleRepository;
use App\Contracts\NotificationSender;
use App\Services\Notifications\LogNotificationSender;

// ...

public function register()
{
    // जब भी कोई ArticleRepository मांगे, उसे EloquentArticleRepository दें।
    $this->app->bind(ArticleRepository::class, EloquentArticleRepository::class);

    // जब भी कोई NotificationSender मांगे, उसे LogNotificationSender दें।
    // अगर कल आपको Email भेजना है, तो बस 'LogNotificationSender' को 'EmailSender' से बदल दें। (OCP Examples)
    $this->app->bind(NotificationSender::class, LogNotificationSender::class);
}
```

-----

### चरण 6: कंट्रोलर (Controller) - (SRP)

कंट्रोलर अब बहुत पतला (thin) हो जाएगा। इसका काम सिर्फ रिक्वेस्ट लेना और सर्विस को कॉल करना है।

```bash
php artisan make:controller ArticleController
```

`app/Http/Controllers/ArticleController.php`

```php
namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $articleService;

    // सर्विस को इंजेक्ट करें
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function store(Request $request)
    {
        // वैलिडेशन (SRP - वैलिडेशन के लिए FormRequest भी यूज़ कर सकते हैं)
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        // सर्विस को काम सौंपें
        $article = $this->articleService->publishArticle($data);

        return response()->json([
            'message' => 'Article published successfully',
            'article' => $article
        ], 201);
    }
}
```

-----

### चरण 7: रूट (Route)

`routes/api.php` में रूट जोड़ें:

```php
use App\Http\Controllers\ArticleController;

Route::post('/articles', [ArticleController::class, 'store']);
```

### यह SOLID कैसे है?

1.  **S (SRP):**
      * `Controller` सिर्फ HTTP रिक्वेस्ट संभाल रहा है।
      * `ArticleService` बिज़नेस लॉजिक को कोऑर्डिनेट कर रहा है।
      * `EloquentArticleRepository` सिर्फ डेटाबेस क्वेरी संभाल रहा है।
      * `LogNotificationSender` सिर्फ लॉगिंग संभाल रहा है।
2.  **O (OCP):** अगर हमें नोटिफिकेशन Email से भेजना है, तो हमें `ArticleService` का कोड बदलने की जरूरत नहीं है। हम बस एक नई क्लास `EmailNotificationSender` बनाएंगे जो `NotificationSender` इंटरफेस को लागू करेगी, और `AppServiceProvider` में बाइंडिंग बदल देंगे।
3.  **D (DIP):** `ArticleService` सीधे `LogNotificationSender` या `EloquentArticleRepository` पर निर्भर नहीं है। यह `NotificationSender` और `ArticleRepository` (इंटरफेसेस) पर निर्भर है। Laravel का सर्विस कंटेनर इन निर्भरताओं को इंजेक्ट करता है।