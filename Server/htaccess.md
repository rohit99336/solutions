# Deploy laravel project using .htaccess file on cPanel

## Prerequisites

- A repository that contains your application code.
- A server that runs cPanel & WHM version 78 or later.
- A cPanel account on the server that contains your application code.
- A domain that resolves to the cPanel account on the server that contains your application code.

## Procedure

1.  Create a .htaccess file in the root directory of your repository.
2.  Add the following code to the .htaccess file:
    ```yaml
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
    ```
3.  Commit and push the .htaccess file to your repository.
4.  Navigate to the cPanel account's domain.
5.  Click the **Deploy** button.
6.  Click **Deploy HEAD Commit**.

## Reference

- [Auto deploying to a server using .cpanel.yml file](https://documentation.cpanel.net/display/CKB/Auto+deploying+to+a+server+using+.cpanel.yml+file)

### My code for .htaccess file

```yaml
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```
