Firebase पर प्रोजेक्ट सेटअप करना और ज़रूरी चाबियाँ (Keys) जनरेट करना बहुत आसान है। यहाँ स्टेप-बाय-स्टेप गाइड दी गई है जिससे आप अपने Laravel 12 प्रोजेक्ट के लिए ज़रूरी क्रेडेंशियल्स निकाल पाएंगे:

### स्टेप 1: Firebase प्रोजेक्ट बनाएं

1. सबसे पहले [Firebase Console](https://console.firebase.google.com/) पर जाएं।
2. **'Add Project'** पर क्लिक करें और अपने प्रोजेक्ट का नाम (जैसे: MyLaravelProject) दें।
3. Google Analytics को 'Enable' या 'Disable' अपनी पसंद के अनुसार रखें और **'Create Project'** पर क्लिक करें।

### स्टेप 2: Web App रजिस्टर करें

1. प्रोजेक्ट डैशबोर्ड पर आपको कुछ आइकॉन्स दिखेंगे (iOS, Android, Web)। **Web आइकॉन (</>)** पर क्लिक करें।
2. अपने ऐप का एक निकनेम दें (जैसे: Laravel-Web-Push)।
3. **'Register App'** पर क्लिक करें।
4. अब आपको एक `firebaseConfig` ऑब्जेक्ट दिखेगा। इसमें आपकी **API Key, Auth Domain, Project ID, App ID** आदि होगी। इसे कॉपी करके अपने प्रोजेक्ट की जावास्क्रिप्ट फाइल में रख लें।

### स्टेप 3: Cloud Messaging चालू करें और VAPID Key जनरेट करें

पुश नोटिफिकेशन भेजने के लिए आपको 'Web Push Certificate' चाहिए होता है:

1. बाएँ हाथ के मेन्यू में ऊपर की तरफ **'Project Settings'** (गियर आइकॉन ⚙️) पर क्लिक करें।
2. ऊपर दिए गए टैब्स में से **'Cloud Messaging'** पर क्लिक करें।
3. नीचे स्क्रॉल करें और **'Web Push certificates'** सेक्शन में जाएं।
4. वहां **'Generate Key Pair'** बटन पर क्लिक करें।
5. जो लम्बी स्ट्रिंग (String) मिलेगी, वही आपकी **VAPID Public Key** है। इसे अपने जावास्क्रिप्ट कोड में `vapidKey` की जगह डालें।

### स्टेप 4: Server Key (Firebase Cloud Messaging API)

Laravel बैकएंड से मैसेज भेजने के लिए आपको सर्वर की अनुमति चाहिए:

1. 'Cloud Messaging' टैब के अंदर ही देखें कि **'Firebase Cloud Messaging API (V1)'** इनेबल है या नहीं। यह अब डिफ़ॉल्ट रूप से इनेबल होता है।
2. अगर आप पुराना 'Legacy Server Key' ढूंढ रहे हैं, तो गूगल अब उसे बंद कर रहा है, इसलिए नया **FCM V1 API** ही इस्तेमाल करें।

### स्टेप 5: Laravel .env में क्रेडेंशियल्स डालें

सुरक्षा के लिए, इन चाबियों को सीधे जावास्क्रिप्ट में न रखकर `.env` फाइल में रखें और वहां से कॉल करें:

```env
FCM_VAPID_PUBLIC_KEY=आपका_पब्लिक_की
FCM_PROJECT_ID=आपका_प्रोजेक्ट_आईडी

```

---

### याद रखने योग्य बातें (Pro-Tips):

* **Service Account File:** अगर आप बैकएंड से `V1 API` का उपयोग कर रहे हैं, तो आपको 'Project Settings' -> **'Service Accounts'** टैब में जाकर **'Generate New Private Key'** पर क्लिक करना होगा। इससे एक JSON फाइल डाउनलोड होगी। इस फाइल को अपने Laravel के `storage` फोल्डर में रखें।
* **Domain Whitelisting:** लाइव सर्वर पर जाने के बाद, 'Authentication' सेक्शन में जाकर अपने डोमेन (जैसे: https://www.google.com/search?q=yourwebsite.com) को **'Authorized Domains'** की लिस्ट में ज़रूर जोड़ें, वरना नोटिफिकेशन ब्लॉक हो सकते हैं।