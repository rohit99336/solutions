### 1. Mailpit (Sabse zyada pasand kiya jane wala)

Mailpit, MailHog ka ek naya aur fast version hai. Ye ek software hai jise aap apne local system par run karte hain. Iska fayda ye hai ki ye **bilkul free** hai aur isme koi email limit nahi hoti.

* **Kaise kaam karta hai:** Aap ise download karke run karte hain, aur Laravel ki `.env` file mein `MAIL_HOST=127.0.0.1` aur `MAIL_PORT=1025` set karte hain.
* **Kahan dekhein:** Aap apne browser mein `http://localhost:8025` par saare emails dekh sakte hain.

### 2. MailHog

Ye Mailpit ki tarah hi hai aur purane developers ke beech kafi mashhoor hai. Agar aap Docker (Laravel Sail) istemal kar rahe hain, to MailHog pehle se hi setup milta hai.

### 3. MailerSend

Ye Mailtrap ka ek bada competitor hai. Inka ek free tier hota hai jisme aap mahine ke kuch hazar emails test kar sakte hain. Ye cloud-based hai, isliye aapko kuch install karne ki zaroorat nahi padti.

### 4. Mailhog.me ya MailSandbox

Ye online tools hain jo aapko ek temporary SMTP server dete hain. Aapko bas unki details apni `.env` file mein dalni hoti hai.

---

### Inhe istemal karne ke liye Laravel Setup:

Aapko apni `.env` file mein niche diye gaye badlav karne honge (Mailpit/MailHog ke liye):

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="test@example.com"

```

---