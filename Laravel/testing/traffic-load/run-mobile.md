Ubuntu लैपटॉप में चल रहे Laravel प्रोजेक्ट को उसी मोबाइल hotspot नेटवर्क पर अपने मोबाइल में आसानी से access कर सकते हैं। इसके लिए आपको Laravel server को केवल `localhost` पर नहीं बल्कि पूरे network पर expose करना होगा।

नीचे पूरा step-by-step process दिया गया है:

---

# तरीका 1 — Laravel Built-in Server से (सबसे आसान)

मान लीजिए आपका Laravel project पहले से चल रहा है।

## Step 1: Project Folder Open करें

Terminal खोलें:

```bash
cd /path/to/your-project
```

उदाहरण:

```bash
cd ~/Projects/myapp
```

---

# Step 2: Laptop का Local IP पता करें

Terminal में चलाएँ:

```bash
ip addr
```

या आसान तरीका:

```bash
hostname -I
```

Output कुछ ऐसा आएगा:

```bash
192.168.43.120
```

यही आपका laptop का hotspot network IP है।

---

# Step 3: Laravel Server को Network पर Run करें

अब सामान्य:

```bash
php artisan serve
```

की जगह यह चलाएँ:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

अब output ऐसा आएगा:

```bash
Starting Laravel development server: http://0.0.0.0:8000
```

---

# Step 4: Mobile में Access करें

अब अपने मोबाइल browser में खोलें:

```text
http://192.168.43.120:8000
```

(ऊपर वाला IP अपने system वाले IP से replace करें)

अब आपका Laravel project mobile में खुल जाएगा।

---

# अगर Access नहीं हो रहा है तो यह करें

Ubuntu firewall block कर सकता है।

## Firewall Status Check करें

```bash
sudo ufw status
```

अगर active है तो port allow करें:

```bash
sudo ufw allow 8000
```

---

# Important बात

मोबाइल और laptop दोनों:

* एक ही hotspot/WiFi पर connected होने चाहिए
* VPN बंद रखें
* Mobile data switching OFF रखें

---

# यदि Vite/NPM Frontend Use कर रहे हैं (Laravel 9/10/11)

अगर आपका project:

* React
* Vue
* Tailwind
* Vite

use करता है, तो केवल Laravel server काफी नहीं होगा।

आपको Vite भी network पर expose करना पड़ेगा।

---

# Vite को Mobile में चलाने का तरीका

## Step 1: vite.config.js Edit करें

```js
export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
    },
});
```

---

## Step 2: Vite Run करें

```bash
npm run dev -- --host
```

या:

```bash
vite --host
```

---

# Step 3: Mobile Browser Refresh करें

अब CSS/JS भी properly load होगा।

---

# HTTPS Issue आ सकता है

यदि आपने `.env` में यह लगाया है:

```env
APP_URL=https://localhost
```

तो उसे बदल दें:

```env
APP_URL=http://192.168.43.120:8000
```

फिर:

```bash
php artisan config:clear
```

चलाएँ।

---

# Production जैसा Testing करना हो तो

आप यह भी use कर सकते हैं:

* Ngrok
* Cloudflare Tunnel

इनसे internet पर public URL मिल जाता है।

---

# सबसे आसान Working Commands

## Laravel

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## IP पता करें

```bash
hostname -I
```

## Mobile में खोलें

```text
http://YOUR_IP:8000
```

---

# Bonus Tip (Web Developer के लिए बहुत उपयोगी)

आप Ubuntu में यह alias बना सकते हैं:

```bash
alias artserve="php artisan serve --host=0.0.0.0 --port=8000"
```

फिर केवल:

```bash
artserve
```

लिखकर server start कर पाएँगे।


<!------------------------------------------------- Part - 2(after info laptop) --------------------------------------------->
Laptop अभी mobile hotspot से connected है और उसका local IP यह है:

```text id="pxbb1z"
10.18.191.102
```

अब आपको बस Laravel server को network पर expose करना है।

---

# Step 1: Laravel Project Folder में जाएँ

```bash id="0v1h1d"
cd /path/to/your-laravel-project
```

उदाहरण:

```bash id="dd9o9f"
cd ~/Projects/myapp
```

---

# Step 2: Laravel Server Run करें

सामान्य command:

```bash id="z0zv7f"
php artisan serve
```

की जगह यह command चलाएँ:

```bash id="w2lcwt"
php artisan serve --host=0.0.0.0 --port=8000
```

---

# Step 3: Mobile में Open करें

अब अपने मोबाइल browser में यह URL खोलें:

```text id="7n5otm"
http://10.18.191.102:8000
```

अब आपका Laravel project मोबाइल में खुल जाना चाहिए ✅

---

# अगर Page Open नहीं हो रहा हो

तो Ubuntu firewall allow करें:

```bash id="jx2m98"
sudo ufw allow 8000
```

फिर दुबारा try करें।

---

# यदि आप React/Vue/Tailwind + Vite Use करते हैं

तो CSS/JS load नहीं होगा जब तक Vite भी expose न करें।

## Vite Start करें

```bash id="iv6g6u"
npm run dev -- --host
```

या:

```bash id="8gk0t6"
vite --host
```

---

# Mobile में CSS/JS नहीं आ रहा?

तो `.env` में यह change करें:

```env id="65e4ks"
APP_URL=http://10.18.191.102:8000
```

फिर:

```bash id="z1t9zc"
php artisan config:clear
```

चलाएँ।

---

# Quick Working Setup

## Terminal 1

```bash id="6l95tu"
php artisan serve --host=0.0.0.0 --port=8000
```

## Terminal 2 (यदि Vite use हो रहा है)

```bash id="8vrm1r"
npm run dev -- --host
```

## Mobile Browser

```text id="w9g7m4"
http://10.18.191.102:8000
```

---

# Bonus (बहुत उपयोगी)

यदि आप बार-बार mobile testing करते हैं तो यह alias add करें:

```bash id="0m7j0u"
nano ~/.bashrc
```

सबसे नीचे add करें:

```bash id="l59y1f"
alias laramobile="php artisan serve --host=0.0.0.0 --port=8000"
```

फिर:

```bash id="mvyu0k"
source ~/.bashrc
```

अब सिर्फ:

```bash id="n08hvl"
laramobile
```

लिखकर server start कर पाएँगे 🚀
