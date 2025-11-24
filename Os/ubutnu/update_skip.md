Ubuntu me **chrome ya kisi bhi package ko update/upgrade hone se rokna** ka sabse safe तरीका hai **package ko hold पर डालना**।

## ✅ तरीका 1: `apt-mark hold` (सबसे सरल और recommend)

मान लीजिए आपको **Google Chrome** update होने से रोकना है:

### **Chrome को hold करने के लिए:**

```bash
sudo apt-mark hold google-chrome-stable
```

### **Hold किए हुए packages देखने के लिए:**

```bash
apt-mark showhold
```

### **Chrome को फिर से update allow करने के लिए:**

```bash
sudo apt-mark unhold google-chrome-stable
```

---

## ✅ तरीका 2: Multiple software को hold करना

मान लो आपको chrome के साथ VSCode और Docker को भी रोकना है:

```bash
sudo apt-mark hold google-chrome-stable code docker-ce docker-ce-cli
```

---

## ❗NOTE:

* Hold करने से **apt update चलेगा**, लेकिन **apt upgrade करते समय ये packages skip होंगे**।
* System safe रहता है, dependency को कोई issue नहीं आता।

---

## 🎯 Bonus: किसी PPA या repository को disable करना

अगर किसी खास software की repo से unwanted updates आ रही हों:

### Example: Chrome repo disable

```bash
sudo nano /etc/apt/sources.list.d/google-chrome.list
```

इस फ़ाइल में `deb` line को comment कर दो:

```
# deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main
```

Save → Exit.

---

## 🎉 Final Recommendation (Best Practice)

सिर्फ़ यह command use करो:

```bash
sudo apt-mark hold google-chrome-stable
```

Same for any package.
