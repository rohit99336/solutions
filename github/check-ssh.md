GitHub server connection बीच में timeout कर रहा है (`HTTP 408`)।

इस स्थिति में सबसे अच्छा और स्थायी समाधान है:

# HTTPS छोड़कर SSH उपयोग करें

SSH पर यह problem लगभग खत्म हो जाती है।

---

# अब यही करें — Step by Step

---

# Step 1 — SSH key check करें

यह command चलाएँ:

```bash id="x3q7m1"
ls ~/.ssh
```

यदि इनमें कुछ दिखे:

```text id="r5v2k8"
id_rsa
id_rsa.pub
```

या

```text id="m9n4p6"
id_ed25519
id_ed25519.pub
```

तो SSH key पहले से बनी है।

---

# यदि कुछ नहीं दिखता

तो नई key बनाएँ:

```bash id="q1w8e4"
ssh-keygen -t ed25519 -C "rohit99336@gmail.com"
```

बस Enter दबाते जाएँ।

---

# Step 2 — SSH agent start करें

```bash id="c7m2x5"
eval "$(ssh-agent -s)"
```

---

# Step 3 — SSH key add करें

```bash id="u4p9k1"
ssh-add ~/.ssh/id_ed25519
```

यदि आपने RSA बनाई है:

```bash id="z8n5v3"
ssh-add ~/.ssh/id_rsa
```

---

# Step 4 — Public key copy करें

```bash id="b2r6m9"
cat ~/.ssh/id_ed25519.pub
```

पूरा output copy करें।

---

# Step 5 — GitHub में key add करें

GitHub खोलें:

[GitHub SSH Settings](https://github.com/settings/keys?utm_source=chatgpt.com)

फिर:

* New SSH Key
* कोई title दें
* copied key paste करें
* Save

---

# Step 6 — Connection test करें

```bash id="k6x1m7"
ssh -T git@github.com
```

यदि यह आए:

```text id="h9q3p2"
Hi Rohit! You've successfully authenticated
```

तो SSH setup complete है ✅

---

# Step 7 — Remote URL बदलें

अभी आपका remote HTTPS है:

```text id="n4v8r1"
https://github.com/rohit99336/metromonial.git
```

इसे SSH में बदलें:

```bash id="f2p7m4"
git remote set-url origin git@github.com:rohit99336/metromonial.git
```

---

# Verify करें

```bash id="j5x8n2"
git remote -v
```

अब ऐसा दिखना चाहिए:

```text id="w1m4q9"
git@github.com:rohit99336/metromonial.git
```

---

# Step 8 — फिर Push करें

```bash id="t8r3m6"
git push -u origin main
```

---

# IMPORTANT

आपका data लगभग upload हो रहा है।
समस्या सिर्फ HTTPS timeout की है।

SSH use करने पर सामान्यतः push तुरंत सफल हो जाता है।

---

# Extra Useful

यदि project में `node_modules` है तो यह भी check करें:

```bash id="g3m9x5"
du -sh node_modules
```

यदि बहुत बड़ा है तो `.gitignore` जरूरी है।

---

# एक और बात

जब `git config --list` पर `(END)` दिखता है,
तो आप `q` दबाकर बाहर निकल सकते हैं।
वह pager (`less`) है, error नहीं।
