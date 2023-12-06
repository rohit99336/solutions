## Commands for setup laravel project from git

### clone project

```bash
git clone repo_url
```

### install php dependencies

```bash
composer install
```

### install node dependencies

```bash
npm install
```

### create .env file (copy from .env.example)

```bash
cp .env.example .env
```

### generate key

```bash
php artisan key:generate
```

### create database

```bash
mysql -u root -p
create database database_name;
```

### update .env file

```bash
DB_DATABASE=database_name
DB_USERNAME=root
DB_PASSWORD=password
```

### migrate database

```bash
php artisan migrate
```

### seed database

```bash
php artisan db:seed
```

### run project

```bash
php artisan serve
```
