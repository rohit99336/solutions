
**पूर्व-आवश्यकताएँ:**

1.  **Laravel प्रोजेक्ट:** आपके पास एक चालू Laravel प्रोजेक्ट होना चाहिए।
2.  **PHP ज्ञान:** आपको PHP और Laravel फ्रेमवर्क का बुनियादी ज्ञान होना चाहिए।
3.  **Composer:** PHP पैकेज मैनेजर (Composer) आपके सिस्टम पर स्थापित होना चाहिए।
4.  **Google Cloud खाता:** आपके पास एक Google Cloud खाता होना चाहिए और Google AI Studio (या Google Cloud Console) के माध्यम से जेमिनी एपीआई के लिए एक एपीआई कुंजी (API Key) प्राप्त करनी होगी।

**चरण-दर-चरण प्रक्रिया:**

**चरण 1: Google AI Studio से एपीआई कुंजी प्राप्त करें**

1.  Google AI Studio (पहले MakerSuite) पर जाएं: [https://aistudio.google.com/](https://aistudio.google.com/)
2.  अपने Google खाते से लॉग इन करें।
3.  "Get API Key" पर क्लिक करें।
4.  एक नई एपीआई कुंजी बनाएं। यह कुंजी आपके Laravel एप्लिकेशन को जेमिनी एपीआई से जुड़ने की अनुमति देगी।
5.  इस कुंजी को सुरक्षित रखें। इसे सीधे अपने कोड में हार्डकोड न करें।

**चरण 2: Laravel प्रोजेक्ट में Composer पैकेज इंस्टॉल करें**

Laravel में जेमिनी एपीआई के साथ इंटरैक्ट करने के लिए, आप Google की आधिकारिक PHP क्लाइंट लाइब्रेरी का उपयोग कर सकते हैं।

अपने Laravel प्रोजेक्ट डायरेक्टरी में टर्मिनल खोलें और निम्नलिखित कमांड चलाएं:

```bash
composer require google/cloud-aiplatform
```

यह `google/cloud-aiplatform` पैकेज और उसकी सभी निर्भरताओं को आपके प्रोजेक्ट में इंस्टॉल करेगा।

**चरण 3: एपीआई कुंजी को Laravel के .env फ़ाइल में सहेजें**

अपनी एपीआई कुंजी को सीधे कोड में नहीं डालना चाहिए। इसके बजाय, इसे आपकी `.env` फ़ाइल में सहेजा जाना चाहिए।

अपनी `.env` फ़ाइल खोलें (जो आपके Laravel प्रोजेक्ट के रूट में स्थित है) और उसमें एक नई प्रविष्टि जोड़ें:

```dotenv
GEMINI_API_KEY=आपकी_जेमिनी_एपीआई_कुंजी_यहां
```

`आपकी_जेमिनी_एपीआई_कुंजी_यहां` को अपनी वास्तविक जेमिनी एपीआई कुंजी से बदलें।

**चरण 4: Laravel में जेमिनी एपीआई के साथ इंटरैक्ट करने के लिए कोड लिखें**

आप एक कंट्रोलर, सर्विस क्लास या यहां तक कि एक नई कमांड बनाकर जेमिनी एपीआई से इंटरैक्ट कर सकते हैं। एक कंट्रोलर का उपयोग करके एक उदाहरण देखें:

**A. एक कंट्रोलर बनाएं:**

```bash
php artisan make:controller GeminiController
```

**B. `app/Http/Controllers/GeminiController.php` फ़ाइल को संपादित करें:**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\AIPlatform\V1\PredictionServiceClient;
use Google\Cloud\AIPlatform\V1\PredictRequest;
use Google\Protobuf\Value;

class GeminiController extends Controller
{
    public function generateContent(Request $request)
    {
        // सुनिश्चित करें कि आपने अपनी API कुंजी को .env फ़ाइल से लोड किया है
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            return response()->json(['error' => 'जेमिनी एपीआई कुंजी कॉन्फ़िगर नहीं है।'], 500);
        }

        // Gemini API से कनेक्ट करने के लिए PredictionServiceClient का उपयोग करें
        // ध्यान दें: Google AI Studio के लिए "endpoint" बदल सकता है,
        // आपको Google AI Studio या Google Cloud दस्तावेज़ में सही एंडपॉइंट ढूंढना होगा।
        // उदाहरण के लिए, "us-central1-aiplatform.googleapis.com" एक सामान्य एंडपॉइंट है।
        // सुनिश्चित करें कि आप सही मॉडल ID का उपयोग कर रहे हैं (उदाहरण के लिए, "gemini-pro" या "gemini-ultra")
        $endpoint = 'us-central1-aiplatform.googleapis.com'; // या आपका विशिष्ट एंडपॉइंट
        $projectId = 'your-google-cloud-project-id'; // यह आपके Google Cloud प्रोजेक्ट का ID होगा
        $location = 'us-central1'; // या आपके मॉडल की भौगोलिक स्थिति
        $modelId = 'gemini-pro'; // या आपके द्वारा उपयोग किए जाने वाले Gemini मॉडल का ID

        $formattedParent = PredictionServiceClient::endpointName($projectId, $location, $endpoint);
        $formattedModel = PredictionServiceClient::modelName($projectId, $location, $modelId);

        try {
            $client = new PredictionServiceClient([
                'credentials' => [
                    'api_key' => $apiKey,
                ],
            ]);

            $prompt = $request->input('prompt', 'नमस्ते, जेमिनी!'); // यूजर से प्रॉम्प्ट प्राप्त करें
            
            // इनपुट के लिए Value ऑब्जेक्ट बनाएं
            $input = Value::fromArray([
                'text_input' => $prompt,
            ]);

            $instances = [$input];
            $parameters = Value::fromArray([
                'temperature' => 0.7, // मॉडल का रचनात्मकता स्तर
                'max_output_tokens' => 200, // अधिकतम आउटपुट टोकन
            ]);

            $predictRequest = (new PredictRequest())
                ->setEndpoint($formattedParent)
                ->setModel($formattedModel)
                ->setInstances($instances)
                ->setParameters($parameters);

            $response = $client->predict($predictRequest);

            $predictions = [];
            foreach ($response->getPredictions() as $prediction) {
                // आपको यहाँ यह पार्स करने की आवश्यकता होगी कि जेमिनी API प्रतिक्रिया को कैसे संरचित करता है।
                // यह JSON, टेक्स्ट या एक जटिल ऑब्जेक्ट हो सकता है।
                // उदाहरण के लिए, यदि यह टेक्स्ट है:
                if ($prediction->offsetExists('text_output')) {
                     $predictions[] = $prediction->offsetGet('text_output');
                } else {
                    // यदि प्रतिक्रिया एक अलग प्रारूप में है, तो इसे डीबग करें और सही ढंग से पार्स करें।
                    $predictions[] = $prediction->serializeToJsonString(); 
                }
            }

            return response()->json(['generated_text' => $predictions]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'जेमिनी एपीआई त्रुटि: ' . $e->getMessage()], 500);
        } finally {
            if (isset($client)) {
                $client->close();
            }
        }
    }
}
```

**महत्वपूर्ण नोट्स:**

  * **`$projectId` और `$location`:** ये आपके Google Cloud प्रोजेक्ट ID और उस क्षेत्र को संदर्भित करते हैं जहां आपका जेमिनी मॉडल डिप्लॉय किया गया है। आपको अपने Google Cloud प्रोजेक्ट में इन मानों को ढूंढना होगा।
  * **`$modelId`:** सुनिश्चित करें कि आप जिस जेमिनी मॉडल (जैसे "gemini-pro") का उपयोग कर रहे हैं, उसका सही आईडी डालें।
  * **प्रतिक्रिया पार्स करना:** जेमिनी एपीआई से मिलने वाली प्रतिक्रिया का सटीक स्वरूप भिन्न हो सकता है। आपको `foreach ($response->getPredictions() as $prediction)` लूप के अंदर `$prediction` ऑब्जेक्ट को लॉग या डंप करके समझना होगा कि डेटा कैसे संरचित है और फिर उसे सही ढंग से पार्स करना होगा (उदाहरण के लिए, यदि यह JSON स्ट्रिंग है, तो `json_decode()` का उपयोग करें)।
  * **त्रुटि हैंडलिंग:** कोड में बुनियादी त्रुटि हैंडलिंग शामिल है, लेकिन उत्पादन वातावरण के लिए अधिक मजबूत त्रुटि हैंडलिंग आवश्यक है।
  * **"Google Cloud AI Platform" बनाम "Google AI Studio"** Google AI Studio एक डेवलपर अनुभव है जो जनरेटिव एआई मॉडल के साथ काम करना आसान बनाता है, जबकि Google Cloud AI Platform उन मॉडलों को डिप्लॉय करने और चलाने के लिए अंतर्निहित इन्फ्रास्ट्रक्चर प्रदान करता है। जेमिनी एपीआई का उपयोग करते समय, आप AI Platform के क्लाइंट लाइब्रेरी का उपयोग करेंगे, भले ही आपने अपनी कुंजी Google AI Studio से प्राप्त की हो।

**चरण 5: रूट परिभाषित करें**

अपनी `routes/api.php` फ़ाइल में (यदि आप एक एपीआई एंडपॉइंट बना रहे हैं) या `routes/web.php` फ़ाइल में (यदि आप सीधे वेब पर इसका उपयोग कर रहे हैं) एक रूट जोड़ें:

```php
// routes/api.php में
use App\Http\Controllers\GeminiController;

Route::post('/generate-content', [GeminiController::class, 'generateContent']);
```

**चरण 6: परीक्षण करें**

आप Postman, Insomnia या cURL जैसे टूल का उपयोग करके अपने एपीआई एंडपॉइंट का परीक्षण कर सकते हैं।

उदाहरण cURL कमांड:

```bash
curl -X POST \
  http://localhost:8000/api/generate-content \
  -H 'Content-Type: application/json' \
  -d '{
    "prompt": "भारत के बारे में 5 रोचक तथ्य क्या हैं?"
  }'
```

**आगे के कदम और विचार:**

  * **सुरक्षा:** अपनी एपीआई कुंजी को सुरक्षित रखें। इसे GitHub या किसी सार्वजनिक रिपॉजिटरी में कभी भी पुश न करें।
  * **दर सीमाएं (Rate Limits):** जेमिनी एपीआई की दर सीमाएं हो सकती हैं। अपने अनुरोधों को तदनुसार प्रबंधित करें।
  * **त्रुटि हैंडलिंग:** उपयोगकर्ता को समझने योग्य त्रुटि संदेश प्रदान करें और विभिन्न प्रकार की API त्रुटियों को संभालें।
  * **मॉडल पैरामीटर:** आप `temperature`, `max_output_tokens`, `top_p`, `top_k` जैसे विभिन्न मॉडल पैरामीटर के साथ प्रयोग कर सकते हैं ताकि उत्पन्न सामग्री को नियंत्रित किया जा सके।
  * **उपयोगकर्ता इंटरफ़ेस (UI):** यदि आप एक वेब एप्लिकेशन बना रहे हैं, तो उपयोगकर्ता को प्रॉम्प्ट इनपुट करने और जेनरेट की गई सामग्री को प्रदर्शित करने के लिए एक फ़्रंटएंड UI (जैसे Vue.js, React, या Blade) बनाएं।
  * **कॉस्ट:** मुफ्त टियर की सीमाओं से अधिक उपयोग करने पर Google Gemini API का उपयोग करने की लागत लग सकती है। Google Cloud मूल्य निर्धारण देखें।
  * **कॉन्टेक्स्ट मैनेजमेंट:** चैटबॉट जैसे अधिक जटिल अनुप्रयोगों के लिए, आपको बातचीत के कॉन्टेक्स्ट को बनाए रखने के लिए पिछली बातचीत को स्टोर और प्रबंधित करना होगा।

इन चरणों का पालन करके, आप अपने Laravel एप्लिकेशन में जेमिनी एआई को सफलतापूर्वक मुफ्त में एकीकृत कर सकते हैं (जब तक आप मुफ्त टियर सीमाओं के भीतर रहते हैं)।