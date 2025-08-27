**ready-made Bash script** - जो Hostinger VPS को reinstall करने के तुरंत बाद run करके उसको secure कर देगा।

---

## 🔐 VPS Security Script (Ubuntu/Debian)

इस script को save करके run करना है:

```bash
#!/bin/bash

# ==========================
# Hostinger VPS Security Script
# ==========================

echo ">>> System Update kar rahe hain..."
apt update && apt upgrade -y

echo ">>> Firewall (UFW) install kar rahe hain..."
apt install ufw -y
ufw allow ssh
ufw allow http
ufw allow https
ufw enable

echo ">>> Fail2ban install kar rahe hain..."
apt install fail2ban -y
systemctl enable fail2ban
systemctl start fail2ban

echo ">>> Unwanted packages remove kar rahe hain..."
apt autoremove -y
apt clean

echo ">>> SSH config secure kar rahe hain..."
# Root login disable & password authentication off
sed -i 's/^#PermitRootLogin.*/PermitRootLogin no/' /etc/ssh/sshd_config
sed -i 's/^#PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config
systemctl restart ssh

echo ">>> Security setup complete ✅"
echo "Ab aap sirf SSH keys se login kar paayenge. Password login disable kar diya gaya hai."
```

---

## 🚀 इस्तेमाल करने का तरीका

1. VPS reinstall करने के बाद **SSH से login** करें।

   ```bash
   ssh root@YOUR_SERVER_IP
   ```

2. Script बनाएं:

   ```bash
   nano secure.sh
   ```

   और ऊपर वाला पूरा code paste कर दें।

3. Script को executable बनाएं:

   ```bash
   chmod +x secure.sh
   ```

4. Script चलाएं:

   ```bash
   ./secure.sh
   ```

---

## ⚠️ Important Note

* ये script चलाने के बाद **root password login बंद हो जाएगा** → यानी आपको SSH key generate करके पहले से VPS में डालना होगा।
* Agar SSH keys set नहीं हैं और आप direct यह चला देंगे, तो आप VPS से lock हो सकते हैं।

👉 इसीलिए पहले मैं recommend करूँगा कि आप चाहें तो मैं आपके लिए एक **version ऐसा बनाऊँ जिसमें root password login disable ना हो, सिर्फ़ basic security (firewall + updates + fail2ban) setup हो** ताकि आप आराम से SSH key बाद में configure कर सकें।

---
