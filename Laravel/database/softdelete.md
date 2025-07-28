---

## ✅ Soft Delete क्या है?

जब आप किसी डेटा को **स्थायी रूप से हटाना (permanent delete)** नहीं चाहते, बल्कि केवल **छिपाना** या भविष्य में वापस लाने के लिए **मार्क करना** चाहते हैं, तो Laravel में "Soft Delete" का उपयोग किया जाता है।

---

## 🔧 Soft Delete को कैसे जोड़ें?

### 1️⃣ **माइग्रेशन में `deleted_at` कॉलम जोड़ें**

```php
$table->softDeletes(); 
```

**उदाहरण:**

```php
Schema::table('users', function (Blueprint $table) {
    $table->softDeletes(); // यह 'deleted_at' नाम का कॉलम बनाएगा
});
```

---

### 2️⃣ **मॉडल में SoftDeletes ट्रेट जोड़ें**

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
}
```

---

## 🔍 Soft Delete कैसे काम करता है?

जब आप डेटा को इस तरह हटाते हैं:

```php
User::find(1)->delete();
```

तो वह रिकॉर्ड डेटाबेस से हटता नहीं है, बल्कि `deleted_at` कॉलम में तारीख और समय दर्ज हो जाता है।

---

## 📋 Soft Delete के मुख्य मेथड्स (उदाहरण सहित)

| मेथड            | कार्य                                     | उदाहरण                                     |
| --------------- | ----------------------------------------- | ------------------------------------------ |
| `delete()`      | रिकॉर्ड को soft delete करता है            | `User::find(1)->delete();`                 |
| `withTrashed()` | सभी (हटाए गए + न हटाए गए) रिकॉर्ड लाता है | `User::withTrashed()->get();`              |
| `onlyTrashed()` | केवल हटाए गए रिकॉर्ड लाता है              | `User::onlyTrashed()->get();`              |
| `restore()`     | हटाए गए रिकॉर्ड को वापस लाता है           | `User::withTrashed()->find(1)->restore();` |
| `forceDelete()` | रिकॉर्ड को पूरी तरह (स्थायी) हटा देता है  | `User::find(1)->forceDelete();`            |

---

## 🎯 पूरा उदाहरण:

```php
// 1. किसी यूज़र को Soft Delete करना
$user = User::find(1);
$user->delete();

// 2. Soft Deleted यूज़र को वापस लाना
User::withTrashed()->find(1)->restore();

// 3. केवल Soft Deleted यूज़र लाना
$deletedUsers = User::onlyTrashed()->get();

// 4. किसी यूज़र को स्थायी रूप से हटाना
User::onlyTrashed()->find(1)->forceDelete();
```

---

## 📝 ध्यान देने योग्य बातें:

* `deleted_at` कॉलम migration में होना ज़रूरी है।
* Laravel की डिफ़ॉल्ट क्वेरीज़ Soft Deleted डेटा को छिपा देती हैं।
* `forceDelete()` से डेटा पूरी तरह मिट जाता है, वापस नहीं लाया जा सकता।

अगर आप चाहें तो मैं इस पर डेमो प्रोजेक्ट या कोड सैंपल भी बना सकता हूँ।
