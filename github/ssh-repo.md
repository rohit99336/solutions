इसका मतलब है कि अभी आपके सिस्टम में SSH key बनी नहीं है।
अब नई SSH key बनानी होगी।

नीचे दिए गए steps बिल्कुल उसी क्रम में करें।

---

# Step 1 — नई SSH key बनाइए

यह command चलाइए:

```bash id="x8m2q5"
ssh-keygen -t ed25519 -C "rohit99336@gmail.com"
```

---

# Step 2 — अब यह पूछेगा

```text id="r4n7p1"
Enter file in which to save the key
```

बस Enter दबा दीजिए।

---

# Step 3 — फिर passphrase पूछेगा

```text id="m9k3v6"
Enter passphrase
```

चाहें तो password दें,
या खाली छोड़ने के लिए Enter दबाएँ।

फिर दुबारा Enter।

---

# Step 4 — Verify करें

अब यह command चलाएँ:

```bash id="u1x5m8"
ls ~/.ssh
```

अब यह files दिखनी चाहिए:

```text id="c7q2r4"
id_ed25519
id_ed25519.pub
```

---

# Step 5 — SSH agent start करें

```bash id="w3m8p1"
eval "$(ssh-agent -s)"
```

---

# Step 6 — SSH key add करें

```bash id="z6r1k9"
ssh-add ~/.ssh/id_ed25519
```

---

# Step 7 — Public key copy करें

```bash id="h4x7m2"
cat ~/.ssh/id_ed25519.pub
```

पूरा output copy करें
जो `ssh-ed25519` से शुरू होगा।

---

# Step 8 — GitHub में add करें

यह page खोलें:

[GitHub SSH Keys](https://github.com/settings/keys?utm_source=chatgpt.com)

फिर:

* New SSH Key
* Title → कुछ भी
* Key → copied text paste करें
* Add SSH Key

---

# Step 9 — Connection test करें

```bash id="q8m4x1"
ssh -T git@github.com
```

पहली बार यह पूछ सकता है:

```text id="n5v2p7"
Are you sure you want to continue connecting
```

लिखें:

```bash id="f1k9r3"
yes
```

---

# यदि सब सही है तो यह आएगा

```text id="p6x3m8"
Hi Rohit! You've successfully authenticated
```

---

# Step 10 — अब remote बदलें

```bash id="t2m7q4"
git remote set-url origin git@github.com:rohit99336/metromonial.git
```

---

# Verify करें

```bash id="j9r5x2"
git remote -v
```

अब ऐसा दिखना चाहिए:

```text id="v4p8m1"
git@github.com:rohit99336/metromonial.git
```

---

# Step 11 — अब push करें

```bash id="m3x6q9"
git push -u origin main
```

अब आपका project सामान्यतः बिना error के GitHub पर upload हो जाएगा।
