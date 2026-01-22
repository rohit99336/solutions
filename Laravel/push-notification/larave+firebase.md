**Laravel 12** और **Firebase (FCM)** के साथ वेब पुश नोटिफिकेशन सेटअप करने के लिए आपको नीचे दिए गए तीन चरणों का पालन करना होगा। यह सेटअप सुनिश्चित करेगा कि यूज़र को नोटिफिकेशन मिले, भले ही वेबसाइट बंद हो।

### चरण 1: डेटाबेस और मॉडल सेटअप

सबसे पहले, हमें हर यूज़र का **FCM Token** स्टोर करना होगा। इसके बिना हम सर्वर को नहीं बता पाएंगे कि नोटिफिकेशन किस डिवाइस पर भेजना है।

**माइग्रेशन फाइल अपडेट करें:**

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('fcm_token')->nullable(); // डिवाइस टोकन स्टोर करने के लिए
});

```

---

### चरण 2: क्लाइंट-साइड (JavaScript) कोड

यह कोड ब्राउज़र में चलेगा। इसका काम यूज़र से परमिशन लेना और टोकन जनरेट करके डेटाबेस में भेजना है। इसे आप अपनी `app.blade.php` या मास्टर लेआउट में रख सकते हैं।

```javascript
// Firebase SDK इम्पोर्ट करें (CDN के जरिए)
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-load-trusted-helper.js";

const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT.firebaseapp.com",
    projectId: "YOUR_PROJECT_ID",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// यूज़र से परमिशन मांगना
function requestPermission() {
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            // टोकन प्राप्त करना
            getToken(messaging, { vapidKey: 'YOUR_VAPID_PUBLIC_KEY' }).then((currentToken) => {
                if (currentToken) {
                    // टोकन को सर्वर (Laravel) पर भेजना
                    saveTokenToDatabase(currentToken);
                }
            });
        }
    });
}

function saveTokenToDatabase(token) {
    fetch('/save-fcm-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ token: token })
    });
}

```

---

### चरण 3: सर्विस वर्कर (Service Worker) सेटअप

यह सबसे महत्वपूर्ण फाइल है। इसे `public/firebase-messaging-sw.js` नाम से बनाएं। यही फाइल **बैकग्राउंड** में नोटिफिकेशन को हैंडल करती है जब ब्राउज़र बंद होता है।

```javascript
importScripts("https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js");

firebase.initializeApp({
    apiKey: "YOUR_API_KEY",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID",
    projectId: "YOUR_PROJECT_ID",
});

const messaging = firebase.messaging();

// बैकग्राउंड नोटिफिकेशन हैंडलर
messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/logo.png'
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

```

---

### चरण 4: Laravel 12 में नोटिफिकेशन भेजना

अब आप अपनी नोटिफिकेशन क्लास में FCM ड्राइवर का उपयोग करके संदेश भेज सकते हैं।

```php
// कंट्रोलर या कहीं भी जहां से भेजना हो
$user = User::find(1);
$user->notify(new OrderNotification($order)); 

```

### कुछ जरूरी बातें (R&D Tips):

1. **VAPID Key:** Firebase कंसोल में 'Project Settings' -> 'Cloud Messaging' में जाकर 'Web Push certificates' जनरेट करें। वही आपकी `vapidKey` होगी।
2. **HTTPS:** याद रखें, यह लोकलहोस्ट के अलावा केवल **HTTPS** पर ही काम करेगा।
3. **Job Queue:** पुश नोटिफिकेशन भेजने के प्रोसेस को हमेशा **Queue** में डालें (`implements ShouldQueue`), क्योंकि Firebase API को कॉल करने में 1-2 सेकंड का समय लग सकता है।
