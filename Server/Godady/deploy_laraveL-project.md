## Deploy Laravel project on cpanel -

### Upload your project on cpanel using file manager
1. Login to your cpanel account. 
2. Go to the file manager and open the public_html folder.
3. Upload your project zip file in the public_html folder.
4. Extract the zip file.
5. Open the extracted folder and select all files and move them to the public_html folder.
6. Open the public_html folder and select all files and move them to the public_html folder.

### Add project on cpanel using git repository  ([Peoject deploy using github](https://github.com/rohit99336/Deployment/blob/main/Godady/deploy_project_using_git.md))
1. Login to your cpanel account.
2. Go to the file manager and open the public_html folder.
3. Click on the create new file button and create a file with the name `.gitignore`.
4. Open the `.gitignore` file and add the following code.
```
*
!.gitignore
```
5. Click on the create new file button and create a file with the name `.cpanel.yml`.
6. Open the `.cpanel.yml` file and add the following code.
```
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/username/public_html
    - /bin/cp -R * $DEPLOYPATH
    - /bin/rm -rf $DEPLOYPATH/.git
    - /bin/rm -rf $DEPLOYPATH/.gitignore
```
7. Replace the username with your cpanel username.
8. Open the terminal and go to your project folder.
9. Run the following command to add the remote repository.
```
git remote add cpanel https://username@domainname:2083/username/public_html
```
10. Replace the username with your cpanel username and domainname with your domain name.
11. Run the following command to push your project on cpanel.
```
git push cpanel master
```
12. Enter your cpanel password and press enter.

### Install composer
1. Open the terminal and go to your project folder.
2. Run the following command to install composer.
```
curl -sS https://getcomposer.org/installer | php
```
3. Run the following command to install composer dependencies.
```
php composer.phar install
```

### Install npm
1. Open the terminal and go to your project folder.
2. Run the following command to install npm.
```
npm install
```
3. Run the following command to install npm dependencies.
```
npm run dev
```

### Create .htaccess file
1. Open the terminal and go to your project folder.
2. Run the following command to create the .htaccess file.
```
touch .htaccess
```
3. Open the .htaccess file and add the following code.
```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Create symlink
1. Open the terminal and go to your project folder.
2. Run the following command to create the symlink.
```
php artisan storage:link
```

### Create database and user
1. Go to the cpanel home page and open the database section.
2. Click on the MySQL Database Wizard.
3. Enter the database name and click on the next step.
4. Enter the username and password and click on the create user.
5. Select the all privileges and click on the next step.
6. Click on the go back button.

### Update .env file
1. Open the .env file and update the database name, username, and password.
2. Update the APP_URL with your domain name.

