हाँ, इन commands से IPv6 हमेशा के लिए disable नहीं होगा।
ये केवल **temporary (अस्थायी)** बदलाव करते हैं।

जब आप system reboot करेंगे, तो अधिकांश Linux distributions में IPv6 फिर से enable हो जाएगा।

---

# इन Commands का मतलब

## ये command:

```bash id="cvl0sa"
sudo sysctl -w net.ipv6.conf.all.disable_ipv6=1
```

पूरे system में IPv6 को temporary disable करती है।

---

## और ये:

```bash id="2f74p8"
sudo sysctl -w net.ipv6.conf.default.disable_ipv6=1
```

नई network interfaces पर IPv6 disable करती है।

---

# Important बात

ये changes:

* reboot तक रहते हैं
* permanent नहीं होते

---

# अगर बाद में वापस enable करना हो

तो simply:

```bash id="efrwwr"
sudo sysctl -w net.ipv6.conf.all.disable_ipv6=0
```

और:

```bash id="6d8v18"
sudo sysctl -w net.ipv6.conf.default.disable_ipv6=0
```

चलाइए।

---

# Permanent Disable कब होता है?

जब आप:

* `/etc/sysctl.conf`
* `/etc/default/grub`
* `/etc/gai.conf`

जैसी files edit करते हैं।

तब reboot के बाद भी IPv6 disabled रहता है।

---

# आपके लिए क्या बेहतर रहेगा?

क्योंकि आपको सिर्फ InfinityFree FTP issue solve करना है, इसलिए:

## पहले temporary method ही use करें

```bash id="1shf8w"
sudo sysctl -w net.ipv6.conf.all.disable_ipv6=1
sudo sysctl -w net.ipv6.conf.default.disable_ipv6=1
```

फिर FileZilla test करें।

अगर काम हो जाए तो:

* deployment के बाद reboot कर दीजिए
* IPv6 वापस आ जाएगा

---

# इससे कोई नुकसान होगा?

सामान्यतः नहीं।

बस temporary तौर पर:

* IPv6-only services नहीं चलेंगी
* बाकी इंटरनेट IPv4 पर normal चलेगा

और भारत में अभी ज्यादातर services IPv4 पर perfectly काम करती हैं।
