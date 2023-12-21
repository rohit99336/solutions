# Install mysql in Ubuntu 
## Install mysql
```bash
sudo apt-get install mysql-server
```
## Configure mysql
```bash
sudo mysql_secure_installation
```

## Configure mysql to allow remote access
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

# Install phpMyAdmin
```bash
sudo apt-get install phpmyadmin
```
or
```bash
Install phpMyAdmin
```
or
```bash
sudo apt-get install phpmyadmin php-mbstring php-gettext
```

## Configure Apache to work with phpMyAdmin
```bash
sudo nano /etc/apache2/apache2.conf
```

## Add the following line at the end of the file:
```bash
Include /etc/phpmyadmin/apache.conf
```

## Restart Apache
```bash
sudo service apache2 restart

// or

sudo systemctl restart apache2
```

## Access phpMyAdmin
```bash
http://localhost/phpmyadmin
```

## Access phpMyAdmin from remote machine
```bash
http://<server_ip>/phpmyadmin
```

## Access phpMyAdmin from remote machine using ssh tunnel
```bash
ssh -L 8080:
```

## Access mysql in terminal 
```bash
mysql -u root -p

// or

sudo mysql -u root -p
```

## Create a new user
```bash
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
```

## Grant the necessary privileges to user newuser for localhost, including the flush privileges command, as follows:
```bash
GRANT ALL PRIVILEGES ON *.* TO 'newuser'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```


## Login to phpMyAdmin using new user credentials
```bash
http://localhost/phpmyadmin
```