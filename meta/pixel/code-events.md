बहुत अच्छा 👍
अब आप **exact सही level** के सवाल पूछ रहे हैं।
मैं आपको **Buy Now / Add to Cart / Checkout / Purchase** — सब कुछ **proper Meta standard** के हिसाब से समझाता हूँ।

---

# 1️⃣ Meta में events के सही नाम क्या होते हैं? (IMPORTANT)

Meta **standard events** को ज़्यादा prefer करता है (ads optimization बेहतर होती है)।

### 🔥 E-commerce के लिए सही events:

| Button / Action | Meta Event Name    |
| --------------- | ------------------ |
| Buy Now         | `InitiateCheckout` |
| Add to Cart     | `AddToCart`        |
| View Product    | `ViewContent`      |
| Order Complete  | `Purchase`         |
| Enquiry / Form  | `Lead`             |

👉 **Custom name तभी दें जब standard fit न हो**

---

# 2️⃣ Add to Cart button में event कैसे लगाएँ

### ✅ HTML button example:

```html
<button onclick="addToCartEvent()">
  Add to Cart
</button>

<script>
function addToCartEvent(){
  fbq('track', 'AddToCart', {
    content_name: 'Vatahari Bati',
    content_category: 'Ayurvedic Product',
    value: 1,
    currency: 'INR'
  });
}
</script>
```

✔️ Meta इसे “AddToCart” मानेगा
✔️ Ads में optimize कर पाएगा

---

# 3️⃣ Buy Now button में event कैसे लगाएँ

### 👉 Buy Now ≠ Purchase

यह usually **checkout start** होता है

```html
<button onclick="buyNowEvent()">
  Buy Now
</button>

<script>
function buyNowEvent(){
  fbq('track', 'InitiateCheckout', {
    content_name: 'Vatahari Bati',
    value: 1,
    currency: 'INR'
  });
}
</script>
```

---

# 4️⃣ Product page load पर ViewContent (Highly Recommended)

```html
<script>
fbq('track', 'ViewContent', {
  content_name: 'Vatahari Bati',
  content_type: 'product'
});
</script>
```

👉 इससे Meta को समझ आता है:

* कौन सा product देखा गया
* Retargeting strong होती है

---

# 5️⃣ Purchase event (सबसे important)

### 🏆 BEST PRACTICE = Thank You Page

Order successful → `/thank-you`

```html
<script>
fbq('track', 'Purchase', {
  content_name: 'Vatahari Bati',
  value: 999,
  currency: 'INR'
});
</script>
```

✔️ Ads optimization
✔️ ROAS tracking
✔️ No duplicate issue

---

# 6️⃣ अगर button form submit करता है (CRITICAL)

### ❌ गलत तरीका:

```html
<button type="submit" onclick="fbq('track','AddToCart')">
```

### ✅ सही तरीका:

```html
<button type="submit" onclick="trackAndSubmit(event)">
Add to Cart
</button>

<script>
function trackAndSubmit(e){
  e.preventDefault();
  fbq('track', 'AddToCart');
  setTimeout(() => e.target.form.submit(), 500);
}
</script>
```

---

# 7️⃣ Custom Event कब बनाते हैं?

अगर कोई action standard में fit नहीं बैठता:

```js
fbq('trackCustom', 'WhatsAppClick');
```

लेकिन ध्यान रखें:
❌ Ads optimization weak
✅ Tracking ठीक

---

# 8️⃣ कैसे verify करें?

### ✅ Meta Events Manager → Test Events

### ✅ Pixel Helper (Chrome)

आपको यह दिखना चाहिए:

* ViewContent
* AddToCart
* InitiateCheckout
* Purchase

---

# 🧠 Final Summary (Clear Rule)

| काम          | Event            |
| ------------ | ---------------- |
| Product देखा | ViewContent      |
| Add to Cart  | AddToCart        |
| Buy Now      | InitiateCheckout |
| Payment done | Purchase         |
| Form submit  | Lead             |

---

## 🔥 Pro Tip (Important)

👉 पहले **ViewContent + AddToCart** ठीक करें
👉 फिर **InitiateCheckout**
👉 सबसे आखिर में **Purchase**

---


=====================================================================================

1️⃣ Meta Pixel कैसे add करें
2️⃣ Standard events कैसे लगाएँ
3️⃣ Custom events कैसे बनाएँ
4️⃣ Button click, form submit, page load पर events कैसे fire करें
5️⃣ Check कैसे करें कि event सही काम कर रहा है

---

# STEP 1️⃣ – Meta Pixel Code Website में लगाना

सबसे पहले आपको Meta से Pixel ID लेना होगा।

### Pixel Code (Example)

इस code को अपनी वेबसाइट के `<head>` में लगाएँ:

```html
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod ?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');

fbq('init', 'YOUR_PIXEL_ID_HERE'); 
fbq('track', 'PageView');
</script>
<noscript>
<img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID_HERE&ev=PageView&noscript=1"/>
</noscript>
<!-- End Meta Pixel Code -->
```

👉 `YOUR_PIXEL_ID_HERE` को अपने pixel ID से replace करें।

---

# STEP 2️⃣ – Page Load पर Event कैसे लगाएँ

जब कोई page खुले, तो:

### Example: Product Page

```html
<script>
fbq('track', 'ViewContent');
</script>
```

---

# STEP 3️⃣ – Button Click पर Event कैसे लगाएँ

मान लीजिए आपके पास "Add to Cart" बटन है:

```html
<button onclick="fbq('track', 'AddToCart');">
  Add to Cart
</button>
```

---

# STEP 4️⃣ – Important Standard Events & Code

---

## ✅ Add to Cart

```html
<button onclick="fbq('track', 'AddToCart');">Add to Cart</button>
```

---

## ✅ Add to Wishlist

```html
<button onclick="fbq('track', 'AddToWishlist');">Wishlist</button>
```

---

## ✅ Initiate Checkout

Checkout page load होने पर:

```html
<script>
fbq('track', 'InitiateCheckout');
</script>
```

---

## ✅ Add Payment Info

```html
<button onclick="fbq('track', 'AddPaymentInfo');">
  Pay Now
</button>
```

---

## ✅ Purchase Event (सबसे जरूरी)

Thank You / Order Success page पर:

```html
<script>
fbq('track', 'Purchase', {
  value: 1999,
  currency: 'INR'
});
</script>
```

---

## ✅ Lead Event (Form Submit)

```html
<form onsubmit="fbq('track', 'Lead');">
```

---

# STEP 5️⃣ – Custom Event कैसे बनाएँ?

अगर Meta का कोई standard event आपके काम का नहीं है, तो आप **Custom Event** बना सकते हैं।

---

## Example: WhatsApp Button Click

```html
<button onclick="fbq('trackCustom', 'WhatsAppClick');">
  Chat on WhatsApp
</button>
```

---

## Example: Call Button

```html
<button onclick="fbq('trackCustom', 'CallButtonClick');">
  Call Now
</button>
```

---

## Example: Video Play

```html
<button onclick="fbq('trackCustom', 'VideoPlayed');">
  Play Video
</button>
```

---

# STEP 6️⃣ – Event के साथ Data कैसे भेजें?

आप extra info भी भेज सकते हैं:

```html
<script>
fbq('track', 'Purchase', {
  value: 2499,
  currency: 'INR',
  product_name: 'Ayurvedic Juice',
  product_id: 'SKU123'
});
</script>
```

---

# STEP 7️⃣ – Laravel में कैसे लगाएँ?

अगर आप Laravel use कर रहे हैं:

### `resources/views/layouts/app.blade.php`

`<head>` में Pixel code लगाएँ।

---

### Button में event

```blade
<button onclick="fbq('track', 'AddToCart')">Add</button>
```

---

### Thank You Page

```blade
<script>
fbq('track', 'Purchase', {
  value: {{ $order->total }},
  currency: 'INR'
});
</script>
```

---

# STEP 8️⃣ – Check कैसे करें कि Event काम कर रहा है?

Chrome में यह extension install करें:

👉 **Meta Pixel Helper**

फिर:

1. Website open करें
2. Extension पर click करें
3. देखिए कौन-कौन से events fire हो रहे हैं

---