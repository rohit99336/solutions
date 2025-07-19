Laravel में माइग्रेशन के दौरान फॉरेन की (Foreign Key) बनाने के मुख्य रूप से दो तरीके हैं, और एक तीसरा तरीका भी है जो `foreignId()` का ही एक विस्तार है। आइए तीनों को समझते हैं:

1.  **`foreign()` method (Traditional Method):**
    यह Laravel में फॉरेन की बनाने का पारंपरिक और सबसे लचीला तरीका है। इसमें आपको फॉरेन की कॉलम को मैन्युअल रूप से डिफाइन करना होता है और फिर उस पर फॉरेन की कंस्ट्रेंट लगाना होता है। यह तब उपयोगी होता है जब आपके कॉलम का नाम या संदर्भित कॉलम का नाम Laravel की डिफ़ॉल्ट नामकरण परंपरा का पालन नहीं करता है, या आपको अधिक विशिष्ट सेटिंग्स (जैसे `onDelete`, `onUpdate`) की आवश्यकता होती है।

    **उदाहरण:**

    ```php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                // फॉरेन की कॉलम को मैन्युअल रूप से डिफाइन करना
                $table->unsignedBigInteger('user_id'); // सुनिश्चित करें कि यह unsigned और सही डेटा टाइप का हो

                $table->timestamps();

                // फॉरेन की कंस्ट्रेंट डिफाइन करना
                $table->foreign('user_id') // आपका फॉरेन की कॉलम
                      ->references('id')   // संदर्भित टेबल का कॉलम
                      ->on('users')        // संदर्भित टेबल का नाम
                      ->onDelete('cascade') // जब पैरेंट रिकॉर्ड डिलीट हो तो क्या करें
                      ->onUpdate('cascade'); // जब पैरेंट रिकॉर्ड अपडेट हो तो क्या करें
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('posts');
        }
    };
    ```

      * `references('id')`: बताता है कि यह `users` टेबल के `id` कॉलम को संदर्भित करेगा।
      * `on('users')`: बताता है कि यह `users` नामक टेबल को संदर्भित करेगा।
      * `onDelete('cascade')`, `onUpdate('cascade')`: ये कंस्ट्रेंट एक्शंस हैं जो बताते हैं कि जब पैरेंट टेबल में संबंधित रिकॉर्ड को डिलीट या अपडेट किया जाता है तो चाइल्ड टेबल के रिकॉर्ड के साथ क्या होना चाहिए। अन्य विकल्प `set null`, `restrict`, `no action` हैं।

2.  **`foreignId()` method (Laravel 7.x से):**
    यह Laravel 7.x में पेश किया गया एक सुविधाजनक तरीका है जो आम मामलों में फॉरेन की को डिफाइन करने के लिए कोड को सरल बनाता है। यह कॉलम बनाता है (`unsignedBigInteger` प्रकार का) और एक ही समय में फॉरेन की कंस्ट्रेंट जोड़ता है, Laravel की नामकरण परंपराओं का पालन करते हुए।

    **उदाहरण:**

    ```php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                // यह user_id कॉलम बनाएगा (unsignedBigInteger)
                // और users टेबल के id कॉलम पर फॉरेन की कंस्ट्रेंट लगाएगा
                $table->foreignId('user_id')->constrained();

                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('posts');
        }
    };
    ```

      * `->constrained()`: यह Laravel को स्वचालित रूप से फॉरेन की कंस्ट्रेंट बनाने के लिए कहता है।
          * डिफ़ॉल्ट रूप से, यह कॉलम नाम (जैसे `user_id`) से संबंधित टेबल (जैसे `users`) और प्राइमरी की (जैसे `id`) का अनुमान लगाता है।
          * यह डिफ़ॉल्ट `onDelete` व्यवहार `RESTRICT` या `SET NULL` (यदि कॉलम nullable है) पर सेट करता है। यदि आप `cascade` या अन्य व्यवहार चाहते हैं, तो आप इसे ऐसे पास कर सकते हैं: `->constrained()->onDelete('cascade');`
      * **कस्टमाइज़ेशन के साथ `constrained()`:**
          * यदि आपका संबंधित टेबल का नाम Laravel के कन्वेंशन (जैसे `user_id` -\> `users` के बजाय `user_id` -\> `accounts`) का पालन नहीं करता है, तो आप `constrained()` में टेबल का नाम पास कर सकते हैं:
            `$table->foreignId('user_id')->constrained('accounts');`
          * यदि संदर्भित टेबल की प्राइमरी की `id` नहीं है (जैसे `uuid`), तो आप उसे भी निर्दिष्ट कर सकते हैं:
            `$table->foreignId('user_id')->constrained('users', 'uuid');`

3.  **`foreignIdFor()` method (Laravel 8.x से):**
    यह `foreignId()` का ही एक विस्तार है जो Eloquent मॉडल का उपयोग करके फॉरेन की कॉलम और कंस्ट्रेंट को परिभाषित करने का एक अधिक सहज तरीका प्रदान करता है। यह विशेष रूप से तब उपयोगी होता है जब आप मॉडल-ड्रिवन डेवलपमेंट पर जोर देते हैं।

    **उदाहरण:**

    ```php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use App\Models\User; // संबंधित मॉडल को इम्पोर्ट करें

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                // यह 'user_id' कॉलम बनाएगा (User मॉडल के नामकरण कन्वेंशन के अनुसार)
                // और users टेबल के id कॉलम पर फॉरेन की कंस्ट्रेंट लगाएगा
                $table->foreignIdFor(User::class)->constrained();

                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('posts');
        }
    };
    ```

      * `foreignIdFor(User::class)`: यह स्वचालित रूप से `user_id` नामक एक कॉलम बनाएगा (जो `User` मॉडल के नाम से आता है) और इसे `unsignedBigInteger` प्रकार का बनाएगा।
      * `->constrained()`: यह `foreignId()` की तरह ही काम करता है, जो डिफ़ॉल्ट रूप से संबंधित मॉडल के टेबल (यानी `users`) और प्राइमरी की (`id`) पर कंस्ट्रेंट लगाता है।
      * आप `constrained()` में कस्टमाइज़ेशन भी पास कर सकते हैं, जैसे `foreignIdFor(User::class, 'author_id')->constrained('users', 'uuid');`

**सारांश में:**

  * **`foreign()`:** सबसे पुराना और सबसे अधिक नियंत्रण देता है। जब आपके पास गैर-मानक नामकरण या जटिल कंस्ट्रेंट व्यवहार हों तो इसका उपयोग करें। आपको कॉलम को मैन्युअल रूप से परिभाषित करना होगा।
  * **`foreignId()`:** Laravel 7+ में आया, यह आम मामलों के लिए एक सरल और संक्षिप्त तरीका है। यह कॉलम को भी परिभाषित करता है। यदि आपके नामकरण कन्वेंशन मानक हैं तो यह बहुत उपयोगी है।
  * **`foreignIdFor()`:** Laravel 8+ में आया, यह `foreignId()` का एक अधिक Eloquent-centric संस्करण है। यह मॉडल क्लास का उपयोग करके फॉरेन की कॉलम का नाम निर्धारित करता है और कंस्ट्रेंट को जोड़ता है।

आपकी आवश्यकता और कोड की पठनीयता के आधार पर, आप इन तरीकों में से किसी का भी उपयोग कर सकते हैं। अधिकांश आधुनिक Laravel अनुप्रयोगों में, `foreignId()` और `foreignIdFor()` को प्राथमिकता दी जाती है क्योंकि वे अधिक संक्षिप्त और पढ़ने में आसान होते हैं।