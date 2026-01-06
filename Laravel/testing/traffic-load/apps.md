### 1. JMeter को Ubuntu पर इंस्टॉल करना

JMeter जावा पर चलता है, इसलिए पहले आपके पास Java होना चाहिए।

* **Java इंस्टॉल करें:** टर्मिनल खोलें और टाइप करें:
`sudo apt update`
`sudo apt install default-jre`
* **JMeter डाउनलोड करें:** 1. [JMeter की वेबसाइट](https://jmeter.apache.org/download_jmeter.cgi) पर जाएं।
2. **Binaries** सेक्शन में से `.tgz` फाइल डाउनलोड करें।
3. इसे अनजिप (Extract) करें और टर्मिनल में उस फोल्डर के अंदर `bin` फोल्डर तक जाएं।
4. इसे चलाने के लिए टाइप करें: `./jmeter.sh`

---

### 2. JMeter में लोड टेस्ट कैसे बनाएं?

JMeter खुलने के बाद, इन स्टेप्स को फॉलो करें:

1. **Thread Group जोड़ें:** `Test Plan` पर राइट क्लिक करें > `Add` > `Threads (Users)` > `Thread Group` चुनें।
* **Number of Threads:** यहाँ लिखें कि आप कितने यूजर्स का लोड डालना चाहते हैं (जैसे 100)।
* **Ramp-up period:** यह वह समय है जिसमें सभी यूजर्स एक्टिव होंगे।


2. **HTTP Request जोड़ें:** `Thread Group` पर राइट क्लिक करें > `Add` > `Sampler` > `HTTP Request` चुनें।
* यहाँ अपने Laravel ऐप का URL (जैसे `localhost` या `127.0.0.1`) और पोर्ट (जैसे `8000`) डालें।


3. **Result देखने के लिए Listener जोड़ें:** `Thread Group` पर राइट क्लिक करें > `Add` > `Listener` > `View Results in Table` या `Summary Report` चुनें।
4. **टेस्ट चलाएं:** ऊपर दिए गए **Green Play** बटन पर क्लिक करें।

---

### 3. अन्य विकल्प (Locust - Python आधारित)

अगर आप कोडिंग (Python) पसंद करते हैं, तो **Locust** एक बहुत ही आधुनिक टूल है जो JMeter से हल्का (lightweight) है।

* **इंस्टॉल करने के लिए:** `pip install locust`
* **फायदा:** इसमें आप टेस्ट केस Python स्क्रिप्ट के रूप में लिखते हैं, जो काफी लचीला होता है।

---

### 4. Laravel के लिए कुछ खास बातें

जब आप लोड टेस्ट करें, तो इन चीजों का ध्यान रखें:

* **CSRF Token:** अगर आप `POST` रिक्वेस्ट (जैसे Login या Form submit) टेस्ट कर रहे हैं, तो Laravel का CSRF प्रोटेक्शन टेस्ट को रोक सकता है। टेस्टिंग के दौरान उस रूट को `VerifyCsrfToken` मिडलवेयर में 'except' लिस्ट में डाल दें।
* **Database:** लोड टेस्ट के दौरान अपने डेटाबेस मॉनिटरिंग को भी देखें कि क्या वह ज्यादा ट्रैफिक पर क्रैश तो नहीं हो रहा।
* **Cache:** लोड टेस्ट चलाने से पहले `php artisan config:cache` और `php artisan route:cache` जरूर चलाएं ताकि Laravel अपनी पूरी क्षमता पर चले।
