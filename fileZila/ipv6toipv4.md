Linux वाले FileZilla version में “Use IPv6 if available” option नहीं दिख रहा है।
लेकिन आपके logs से साफ पता चल रहा है कि system IPv6 use कर रहा है:

```text id="0a6tvx"
Connecting to [64:ff9b::b91b:860b]:21
```

इसलिए अब FileZilla settings से ज्यादा जरूरी है Linux में IPv6 disable करना।

# अभी यही करें

Terminal खोलिए और ये command चलाइए:

```bash id="brl8yt"
sudo sysctl -w net.ipv6.conf.all.disable_ipv6=1
```

फिर:

```bash id="0jw4r5"
sudo sysctl -w net.ipv6.conf.default.disable_ipv6=1
```

अब check करें:

```bash id="n0w5z5"
cat /proc/sys/net/ipv6/conf/all/disable_ipv6
```

अगर output:

```text id="5w8vh0"
1
```

आ जाए तो IPv6 disable हो चुका है।

---

# अब क्या करें

1. FileZilla पूरी तरह बंद करें
2. दुबारा open करें
3. Reconnect करें

---

# Successful होने पर log ऐसा दिखेगा

```text id="2l8z2i"
Connecting to 185.xxx.xxx.xxx:21
```

ध्यान दें:

* अब `64:ff9b::` नहीं दिखना चाहिए
* केवल normal IPv4 दिखना चाहिए

---

# अगर फिर भी same issue आए

तो ये command चलाइए:

```bash id="0o4gvr"
getent ahosts ftpupload.net
```

अगर output में IPv6 दिखे तो DNS अभी भी IPv6 दे रहा है।

---

# तब Final Fix करें

## `/etc/gai.conf` edit करें

Terminal में:

```bash id="uxnl6t"
sudo nano /etc/gai.conf
```

फिर ये line ढूंढें:

```text id="n69vvv"
#precedence ::ffff:0:0/96  100
```

उसका `#` हटा दें:

```text id="n9q3hl"
precedence ::ffff:0:0/96  100
```

फिर save करें:

```text id="1qfc0t"
CTRL + O
ENTER
CTRL + X
```

अब system IPv4 prefer करेगा।

---

# फिर Reboot करें

```bash id="l94jz0"
sudo reboot
```

---

# उसके बाद InfinityFree FTP लगभग निश्चित रूप से connect हो जाएगा।
