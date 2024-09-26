```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redirect all requests from public_html to the knsa folder
    RewriteCond %{REQUEST_URI} !^/knsa/
    RewriteRule ^(.*)$ /knsa/$1 [L]

    # If the knsa folder is the application root, apply further rules
    <IfModule mod_rewrite.c>
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . /knsa/index.php [L]
    </IfModule>

</IfModule>
```
