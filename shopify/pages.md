Shopify में आपने जो **Contact Form Section** बनाया है, उसे **`/pages/contact`** पर दिखाने के लिए आपको दो चीजें करनी होंगी:

---

## ✅ **Step-by-Step Setup: Contact Section on `/pages/contact`**

### 🔸 **1. Contact Page Template बनाएं**

1. **Theme Editor → Code Editor** में जाएं (`Online Store > Themes > Edit Code`)
2. `templates` फ़ोल्डर में जाएं
3. नया फ़ाइल बनाएं:
   ➤ **File Type:** `JSON`
   ➤ **File Name:** `page.contact.json`

👉 इस फ़ाइल में नीचे जैसा JSON डालें:

```json
{
  "name": "Contact Page",
  "sections": {
    "main": {
      "type": "contact-section"
    }
  },
  "order": ["main"]
}
```

> ⚠️ यहाँ `"contact-section"` वही होना चाहिए जो आपने ऊपर `section` के अंदर `"name"` और फाइल के नाम में लिखा है।
> अगर आपकी section फाइल का नाम `contact-form.liquid` है तो `"type": "contact-form"` लिखें।

---

### 🔸 **2. Contact Page बनाएँ और Template से जोड़ें**

1. **Shopify Admin → Online Store → Pages** में जाएँ
2. **"Contact"** नाम से एक नया page बनाएँ या मौजूदा `/pages/contact` पेज को edit करें
3. Right side में “**Theme template**” का ऑप्शन देखें
4. Template drop-down में से `page.contact` चुनें
5. Save कर दें

---

### 🔸 **3. Section File Save करें (अगर नहीं किया है)**

1. `sections` फ़ोल्डर में जाएँ
2. `contact-section.liquid` नाम से फाइल बना लें (या `contact-form.liquid`)
3. उसमें ऊपर दिया गया Bootstrap 5 contact form section code paste करें और save करें

---

### 🟢 अब `/pages/contact` URL पर आपका नया Bootstrap वाला Contact Form show होगा!

---

अगर आप चाहें तो हम:

* इस page में left/right image जोड़ सकते हैं
* thank you message को redirect कर सकते हैं
* success message को styled alert box से highlight कर सकते हैं

क्या इन में से कुछ और भी करना चाहेंगे?
