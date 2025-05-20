Here's a comprehensive **Composer Cheat Sheet** for PHP projects, tailored for Ubuntu (or any Unix-like system):

---

# 🧾 Composer Cheat Sheet

> Composer is a dependency manager for PHP, used to install, update, and manage packages.

---

## 📌 Basic Commands

| Command                           | Description                                                     |
| --------------------------------- | --------------------------------------------------------------- |
| `composer init`                   | Starts a wizard to create `composer.json`.                      |
| `composer install`                | Installs dependencies from `composer.lock`.                     |
| `composer update`                 | Updates dependencies as per `composer.json`.                    |
| `composer require vendor/package` | Adds a package and updates `composer.json` and `composer.lock`. |
| `composer remove vendor/package`  | Removes a package.                                              |
| `composer dump-autoload`          | Regenerates the `autoload` files. Use after adding new classes. |
| `composer validate`               | Validates the `composer.json` file.                             |

---

## 🔍 Info & Diagnosis

| Command                        | Description                             |
| ------------------------------ | --------------------------------------- |
| `composer show`                | Shows installed packages.               |
| `composer show vendor/package` | Shows details about a specific package. |
| `composer outdated`            | Lists outdated packages.                |
| `composer diagnose`            | Checks for common errors in setup.      |

---

## 🔧 Global Operations

| Command                                  | Description                                           |
| ---------------------------------------- | ----------------------------------------------------- |
| `composer global require vendor/package` | Installs a package globally (like Laravel installer). |
| `composer global update`                 | Updates all global packages.                          |

Make sure global composer binaries are in your `PATH`:

```bash
export PATH="$HOME/.config/composer/vendor/bin:$PATH"
```

---

## 🔄 Version Control

| Command                           | Description                         |
| --------------------------------- | ----------------------------------- |
| `composer self-update`            | Updates Composer to latest version. |
| `composer self-update --1`        | Downgrades to Composer 1.x.         |
| `composer self-update --2`        | Upgrades to Composer 2.x.           |
| `composer self-update --rollback` | Reverts to previous version.        |

---

## ⚙️ Scripts (In `composer.json`)

You can define custom scripts:

```json
"scripts": {
  "post-update-cmd": "php artisan migrate"
}
```

Run with:

```bash
composer run-script post-update-cmd
```

---

## 📁 Important Files

* **`composer.json`** – Lists required packages and metadata.
* **`composer.lock`** – Locks the exact versions of installed dependencies.

Never edit `composer.lock` manually. Commit both files to version control.

---

## 📦 Custom Package Repositories

Add custom repo in `composer.json`:

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/your/package"
  }
]
```

---

## ✅ Example Workflow

```bash
composer init                     # Initialize project
composer require guzzlehttp/guzzle  # Add HTTP client
composer install                 # Install dependencies
composer dump-autoload           # Regenerate autoload files
composer update                  # Update all packages
```

---
