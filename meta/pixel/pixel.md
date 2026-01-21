Meta Pixel के सभी 17 Standard Events और Custom Event को मैन्युअल रूप से कोड के जरिए कैसे लगाना है, इसकी पूरी जानकारी नीचे दी गई है।

### 1. सभी 17 Standard Events के कोड

इन कोड्स को आपको उन खास पेजों के `<head>` टैग में या बटन के क्लिक पर लगाना होता है जहाँ वह एक्शन हो रहा है।

| No. | Event Name | Manual Code | कब इस्तेमाल करें? |
| --- | --- | --- | --- |
| 1 | **Add Payment Info** | `fbq('track', 'AddPaymentInfo');` | चेकआउट के दौरान पेमेंट डिटेल्स डालने पर। |
| 2 | **Add to Cart** | `fbq('track', 'AddToCart');` | जब कोई सामान कार्ट में ऐड करे। |
| 3 | **Add to Wishlist** | `fbq('track', 'AddToWishlist');` | जब कोई प्रोडक्ट विशलिस्ट में डाले। |
| 4 | **Complete Registration** | `fbq('track', 'CompleteRegistration');` | जब कोई अकाउंट बना ले या साइन-अप पूरा करे। |
| 5 | **Contact** | `fbq('track', 'Contact');` | जब कोई फोन, ईमेल या चैट के जरिए संपर्क करे। |
| 6 | **Customize Product** | `fbq('track', 'CustomizeProduct');` | जब कोई प्रोडक्ट का रंग या साइज़ कस्टमाइज़ करे। |
| 7 | **Donate** | `fbq('track', 'Donate');` | जब कोई संस्था को दान (Donation) दे। |
| 8 | **Find Location** | `fbq('track', 'FindLocation');` | जब कोई आपके स्टोर की लोकेशन सर्च करे। |
| 9 | **Initiate Checkout** | `fbq('track', 'InitiateCheckout');` | जब कोई चेकआउट बटन दबाकर पेमेंट की तरफ बढ़े। |
| 10 | **Lead** | `fbq('track', 'Lead');` | जब कोई फॉर्म भरकर अपनी जानकारी दे। |
| 11 | **Purchase** | `fbq('track', 'Purchase', {value: 0.00, currency: 'INR'});` | जब खरीदारी पूरी हो जाए (Order Success)। |
| 12 | **Schedule** | `fbq('track', 'Schedule');` | जब कोई अपॉइंटमेंट बुक करे। |
| 13 | **Search** | `fbq('track', 'Search');` | जब कोई आपकी साइट पर कुछ सर्च करे। |
| 14 | **Start Trial** | `fbq('track', 'StartTrial', {value: 0.00, currency: 'INR'});` | जब कोई फ्री ट्रायल शुरू करे। |
| 15 | **Submit Application** | `fbq('track', 'SubmitApplication');` | जब कोई नौकरी या कोर्स के लिए अप्लाई करे। |
| 16 | **Subscribe** | `fbq('track', 'Subscribe', {value: 0.00, currency: 'INR'});` | जब कोई पेड सब्सक्रिप्शन खरीदे। |
| 17 | **View Content** | `fbq('track', 'ViewContent');` | जब कोई खास प्रोडक्ट पेज या आर्टिकल देखे। |

---

### 2. Custom Event कैसे बनाएं? (Step-by-Step)

अगर आपको कोई ऐसा इवेंट चाहिए जो ऊपर की लिस्ट में नहीं है (जैसे: "Video_Watched_50%"), तो उसे ऐसे बनाएं:

* **Step 1:** नाम सोचें (बिना स्पेस के, जैसे: `PromoCodeApplied`)।
* **Step 2:** कोड में `track` की जगह `trackCustom` का इस्तेमाल करें।
* **Step 3:** कोड लिखें:
```javascript
<script>
  fbq('trackCustom', 'PromoCodeApplied', {promo_name: 'SAVE50'});
</script>

```



---

### 3. पेज में लगाने का सही तरीका (Standard & Custom)

मान लीजिए आपको 'Purchase' इवेंट लगाना है, तो आपका HTML पेज ऐसा दिखेगा:

```html
<!DOCTYPE html>
<html lang="hi">
<head>
    <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', 'YOUR_PIXEL_ID_HERE'); 
      fbq('track', 'PageView');
    </script>
    <script>
      // खरीदारी ट्रैक करने के लिए
      fbq('track', 'Purchase', {
        value: 1200.00, 
        currency: 'INR'
      });

      // अपना बनाया हुआ कस्टम इवेंट
      fbq('trackCustom', 'UserRankedProduct', {rank: '5_Star'});
    </script>
</head>
<body>
    <h1>धन्यवाद! आपकी खरीदारी सफल रही।</h1>
</body>
</html>

```

---

### 4. ध्यान रखने योग्य बातें:

1. **Parameters (पैरामीटर्स):** Purchase, Subscribe और Start Trial के साथ `{value, currency}` भेजना जरूरी है ताकि आप Facebook Ads मैनेजर में ROI (Return on Investment) देख सकें।
2. **Order Success Page:** Purchase इवेंट को हमेशा 'Thank You' या 'Order Success' पेज पर ही लगाएं, होमपेज पर नहीं।
3. **Verification:** कोड लगाने के बाद 'Meta Pixel Helper' एक्सटेंशन से चेक करें। अगर कोड सही है, तो वह नीले रंग का हो जाएगा और आपके द्वारा लगाए गए इवेंट वहां लिस्ट में दिखेंगे।

**क्या आप चाहते हैं कि मैं आपको किसी खास प्लेटफॉर्म (जैसे WordPress या Custom PHP) के लिए कोई विशिष्ट कोड लिखकर दूँ?**