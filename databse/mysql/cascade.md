ठीक है 👍
MySQL में **CASCADE** का मतलब है — जब आप किसी **foreign key** (विदेशी कुंजी) रिलेशन पर कोई ऐक्शन करते हैं (जैसे DELETE या UPDATE), तो उससे जुड़े हुए child table (निर्भर टेबल) में भी वही ऐक्शन अपने-आप हो जाए।

### प्रकार:

1. **ON DELETE CASCADE**
   अगर parent टेबल से कोई row delete हुई → child टेबल में उससे जुड़े rows भी अपने-आप delete हो जाएँगे।

2. **ON UPDATE CASCADE**
   अगर parent टेबल की primary key update हुई → child टेबल में foreign key भी अपने-आप update हो जाएगी।

---

### Example

```sql
CREATE TABLE users (
    id INT PRIMARY KEY,
    name VARCHAR(100)
);

CREATE TABLE orders (
    id INT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

अब अगर `users` टेबल से कोई user delete करेंगे → उस user के सारे `orders` अपने-आप delete हो जाएँगे।

---

👉 संक्षेप में:
**CASCADE = parent table पर होने वाला delete/update अपने-आप child table पर भी लागू होना।**