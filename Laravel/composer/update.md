To update Composer on Ubuntu, you can follow these steps:

---

### ✅ **Step 1: Check your current Composer version**

```bash
composer --version
```

---

### ✅ **Step 2: Update Composer (Globally)**

Run the following command to update Composer to the latest stable version:

```bash
sudo composer self-update
```

If you're using Composer 2 and want to ensure you're on the latest version within major version 2:

```bash
sudo composer self-update --2
```

If you need to switch back to version 1 for any reason:

```bash
sudo composer self-update --1
```

---

### ✅ **Step 3: Verify the update**

After the update completes, check the version again:

```bash
composer --version
```

---

### 🛠️ **Alternative: Reinstall Composer (if needed)**

If `self-update` doesn't work or you're setting up Composer for the first time, run:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
php -r "unlink('composer-setup.php');"
```

---

Let me know if you're using PHP via a specific version manager (like `phpbrew` or `valet`), or if it's installed via a local path—you might need a slightly different approach.
