**safe version** (जिसमें root password login disable **नहीं** होगा)।
इससे आप आराम से login करते रहेंगे, और बाद में SSH keys configure करके चाहें तो password login बंद कर सकते हैं।

---

## 🔐 VPS Security Script (Safe Version)

```bash
#!/bin/bash

# ==========================
# Hostinger VPS Security Script (Safe Version)
# ==========================

echo ">>> System Update kar rahe hain..."
apt update && apt upgrade -y

echo ">>> Firewall (UFW) install kar rahe hain..."
apt install ufw -y
ufw allow ssh
ufw allow http
ufw allow https
ufw --force enable

echo ">>> Fail2ban install kar rahe hain..."
apt install fail2ban -y
systemctl enable fail2ban
systemctl start fail2ban

echo ">>> Unwanted packages remove kar rahe hain..."
apt autoremove -y
apt clean

echo ">>> Security setup complete ✅"
echo "Ab aapka VPS updated hai, firewall active hai aur brute-force protection (fail2ban) lag chuka hai."
echo "Filhal root password login chal raha hai (disable nahi kiya gaya)."
echo "Aap chahe to SSH key setup karne ke baad password login disable kar sakte hain."
```

---

## 🚀 इस्तेमाल कैसे करना है

1. VPS reinstall करने के बाद SSH से login करें:

   ```bash
   ssh root@YOUR_SERVER_IP
   ```
2. Script बनाएं:

   ```bash
   nano secure.sh
   ```

   और ऊपर वाला पूरा code paste कर दें।
3. Script executable बनाएं:

   ```bash
   chmod +x secure.sh
   ```
4. Script चलाएं:

   ```bash
   ./secure.sh
   ```

---

✅ इससे आपका VPS safe रहेगा:

* सारे packages update होंगे
* Firewall लगेगा (sirf SSH, HTTP, HTTPS open)
* Fail2ban brute-force attack रोकेगा

---