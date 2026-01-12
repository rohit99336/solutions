Shared Hosting (सर्वर) पर सुरक्षा कारणों से **`proc_open`** और **`proc_get_status`** जैसे PHP फंक्शन्स को डिसेबल (Disabled) कर दिया गया है।

Laravel का Scheduler और `Symfony\Process` क्लास इन फंक्शन्स का इस्तेमाल बैकग्राउंड टास्क चलाने के लिए करते हैं। इसे ठीक करने के लिए आपके पास दो मुख्य रास्ते हैं:

---

### **समाधान 1: PHP `disable_functions` से हटाना (सबसे आसान)**

अगर आपके पास **cPanel** या **DirectAdmin** का एक्सेस है, तो आप खुद इसे इनेबल कर सकते हैं:

1. अपने **cPanel** में लॉगिन करें।
2. **"Select PHP Version"** सर्च करें और उस पर जाएं।
3. वहाँ **"Options"** टैब पर क्लिक करें।
4. नीचे स्क्रॉल करें और **`disable_functions`** वाली फील्ड ढूंढें।
5. वहाँ से `proc_open` और `proc_get_status` को हटा दें और सेव कर दें।

> **नोट:** अगर आप यह फील्ड नहीं देख पा रहे हैं, तो इसका मतलब है कि आपका होस्टिंग प्रोवाइडर इसे बदलने की अनुमति नहीं देता। ऐसे में आपको उनके **Support** को ईमेल करना होगा कि "Please enable `proc_open` for my account so I can run Laravel Scheduler."

---

### **समाधान 2: `schedule:run` के बजाय सीधा कमांड चलाना (Workaround)**

यदि होस्टिंग वाले `proc_open` इनेबल नहीं करते, तो आप Cron Job को चलाने का तरीका बदल सकते हैं।

आमतौर पर हम Cron Job ऐसे सेट करते हैं:
`* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1`

लेकिन `schedule:run` अंदरूनी तौर पर `proc_open` मांगता है। इसकी जगह आप **सीधे अपनी कमांड** को Cron Job में डाल दें:

```bash
# schedule:run के बजाय सीधा FetchEmails चलाएं
* * * * * /usr/local/bin/php /home/maulitraveldest/domains/maulitraveldestination.com/public_html/artisan emails:fetch >> /dev/null 2>&1

```

इससे Laravel का "Process" मैनेजर बायपास हो जाएगा और आपकी कमांड सीधे PHP के जरिए चलेगी।

---

### **समाधान 3: `php.ini` फाइल के जरिए**

अगर आपके प्रोजेक्ट के रूट फोल्डर में `php.ini` फाइल काम करती है, तो उसमें यह लाइन जोड़कर देखें:

```ini
disable_functions = 

```

(यह सभी डिसेबल फंक्शन्स को खाली कर देगा, लेकिन अधिकतर सर्वर्स इसे ब्लॉक रखते हैं)।

### **अगला कदम क्या लें?**

सबसे पहले अपने cPanel में जाकर देखें कि क्या आप PHP Version Options में बदलाव कर सकते हैं। अगर नहीं, तो **समाधान 2** (Cron Job में सीधा कमांड डालना) सबसे सुरक्षित और प्रभावी तरीका है।
