step-by-step - **Hostinger VPS को reinstall करके secure** ।

---

## 🔄 Step 1: VPS Reinstall करना

1. Hostinger के **hPanel → VPS → Manage** में जाएं।
2. वहाँ आपको option मिलेगा **Operating System → Reinstall**.
3. कोई साफ़ OS चुनें (जैसे Ubuntu 22.04 LTS).
4. Reinstall confirm करें → इससे VPS बिलकुल clean slate पर चला जाएगा।

⚠️ Warning: Reinstall करने से VPS का सारा data मिट जाएगा।

---

## 🔒 Step 2: Root Password बदलना

1. Reinstall के बाद Hostinger आपको **नया root password** देगा।
2. सबसे पहले SSH से login करें:

   ```bash
   ssh root@YOUR_SERVER_IP
   ```
3. Root password तुरंत बदलें:

   ```bash
   passwd
   ```

---

## 🛡 Step 3: Security Setup करना

अब VPS secure करना ज़रूरी है ताकि दोबारा hack न हो।

### (A) System Update

```bash
apt update && apt upgrade -y
```

### (B) Firewall (UFW) Install और Configure

```bash
apt install ufw -y
ufw allow ssh
ufw allow http
ufw allow https
ufw enable
```

👉 इससे सिर्फ़ जरूरी ports खुले रहेंगे (22, 80, 443)।

### (C) Root Login Secure करना

SSH config edit करें:

```bash
nano /etc/ssh/sshd_config
```

* यह lines बदलें:

  ```
  PermitRootLogin no
  PasswordAuthentication no
  ```
* अब सिर्फ़ SSH key से login होगा (password से नहीं)।
  फिर SSH restart करें:

```bash
systemctl restart ssh
```

### (D) SSH Key Setup (Recommended)

Apne computer par SSH key banaiye (agar nahi hai):

```bash
ssh-keygen -t rsa -b 4096
```

Public key VPS me copy kijiye:

```bash
ssh-copy-id root@YOUR_SERVER_IP
```

### (E) Fail2ban Install (Brute-force रोकने के लिए)

```bash
apt install fail2ban -y
systemctl enable fail2ban
systemctl start fail2ban
```

---

## ✅ Final Step: Safe Usage

* VPS me unnecessary software install na karein.
* Har बार updates करते रहें.
* Logs check करते रहें (`/var/log/auth.log`).
* Agar aap WordPress, Laravel, ya Node.js host karna chahte हैं तो proper hardening guide follow करें।

---

👉 अब आपके VPS पर दोबारा **crypto mining malware** नहीं चल पाएगा।

---