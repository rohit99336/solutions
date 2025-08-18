बिलकुल! नीचे मैं आपको **Oracle Cloud Free Tier** में **Laravel Project deploy** करने का पूरा step-by-step guide दूँगा — हिंदी में और आसान भाषा में।

---

## 🧾 Oracle Cloud Free Tier Overview

Oracle Cloud आपको *हमेशा के लिए फ्री* में ये देता है:

* ✅ 2 VM instances (Ubuntu/Linux) – **Always Free**
* ✅ 1 GB RAM, 1/8 OCPU – ठीक है Laravel के लिए
* ✅ 100 GB block storage (SSD)
* ✅ Public IP (Free)
* ✅ Free Firewall + Networking

---

## 🛠️ Step-by-Step Guide – Laravel को Deploy करना Oracle Cloud पर

### 🔹 Step 1: Oracle Cloud में Free Account बनाएँ

1. जाएँ: [https://www.oracle.com/cloud/free/](https://www.oracle.com/cloud/free/)
2. Sign Up करें (आपका **PAN**, **Mobile Number**, और **Credit/Debit Card** माँगा जाएगा)
3. Email verification के बाद लॉगिन करें Oracle Cloud Console में

---

### 🔹 Step 2: Free VM (Compute Instance) बनाना

1. Dashboard में जाएँ: **Compute → Instances**
2. Click करें: **Create Instance**
3. Name दें: `laravel-vm`
4. Image: Ubuntu 22.04 (recommended)
5. Shape: Click “Change Shape” → `Ampere (ARM)` → Choose **VM.Standard.A1.Flex**

   * CPU: 1 OCPU
   * Memory: 1 GB
6. Networking: Default VCN
7. Add SSH Key:

   * अगर आपके पास SSH Key नहीं है, तो generate करें:

     ```bash
     ssh-keygen -t rsa -b 2048
     ```

     फिर `~/.ssh/id_rsa.pub` का content copy करें और paste करें "Add SSH Key" में।
8. Create करें

⏳ यह 1-2 मिनट लेगा।

---

### 🔹 Step 3: SSH से VM में Login करें

```bash
ssh -i ~/.ssh/id_rsa ubuntu@<your_public_ip>
```

---

### 🔹 Step 4: Server Setup करें (Ubuntu के अंदर)

#### 📦 जरूरी packages install करें:

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install php php-cli php-mbstring php-xml php-bcmath php-curl php-mysql php-zip unzip git curl nginx mysql-server composer -y
```

#### ✔️ Laravel के लिए फोल्डर बनाएँ:

```bash
cd /var/www
sudo git clone https://github.com/your-repo/laravel-app.git laravel
cd laravel
composer install
cp .env.example .env
php artisan key:generate
```

---

### 🔹 Step 5: MySQL Configure करें

```bash
sudo mysql
```

```sql
CREATE DATABASE laraveldb;
CREATE USER 'laraveluser'@'localhost' IDENTIFIED BY 'yourpassword';
GRANT ALL PRIVILEGES ON laraveldb.* TO 'laraveluser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

`.env` फ़ाइल में DB config अपडेट करें:

```
DB_DATABASE=laraveldb
DB_USERNAME=laraveluser
DB_PASSWORD=yourpassword
```

फिर:

```bash
php artisan migrate
```

---

### 🔹 Step 6: Nginx Configure करें

```bash
sudo nano /etc/nginx/sites-available/laravel
```

```nginx
server {
    listen 80;
    server_name your_domain_or_ip;

    root /var/www/laravel/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;  # adjust PHP version
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable करें:

```bash
sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

### 🔹 Step 7: UFW Firewall और Oracle Cloud Firewall Open करें

#### VM में:

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

#### Oracle Console में:

* VCN → Subnet → Security List → Ingress Rules में:

  * Allow: TCP, Port 80, 443, 0.0.0.0/0

---

### ✅ अब आप Laravel App ब्राउज़र में खोल सकते हैं:

```
http://<your_public_ip>
```

---

## ✅ Tips:

* SSL के लिए use करें Let’s Encrypt:

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx
```

* Background workers के लिए: Supervisor install करें
* Cron jobs के लिए: `crontab -e` में Laravel scheduler जोड़ें

---