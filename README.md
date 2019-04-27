PleasePay REST API Demo Version 1.0 
=============

Services 
--------

#### Brief description of available services. For usage please see examples under tests/*
- POST /1.0/auth 
- POST /1.0/auth/logout 
- POST /1.0/auth/reset 

- GET  /1.0/buyer [Header: Client-Security-Token]
- GET  /1.0/buyer/{id}
- POST /1.0/buyer

- GET  /1.0/country
- GET  /1.0/currency

- GET  /1.0/dashboard 

- GET  /1.0/seller/welcome/{to}
- POST /1.0/seller

- GET  /1.0/invoice
- GET  /1.0/invoice/{id}
- POST /1.0/invoice

- GET  /1.0/invoice_print
- POST /1.0/invoice_product

- POST /1.0/logo

- GET  /1.0/product/{name}

- POST /1.0/update/seller
- GET  /1.0/vat


Installation
------------

#### Requirements
- You have to have composer installed.
- You have to set up CORS on your web server (See CORS section below
  for apache example)
- You have to create a db and user (for names and passwords look at
  _app/app.php_)
  
```bash
composer install # to install vendor dependencies
./vendor/bin/phinx migrate # to apply db migrations
```

### After adding psr-4 namespace dont forget to do composer dump-autoload


### Create new migration
```bash
php vendor/bin/phinx create MyNewMigration
```

### Run migration
```bash
php vendor/bin/phinx migrate
```

### htaccess file
Working one
```
<IfModule mod_rewrite.c>
    Options -MultiViews

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

The following one didnt work on live server
```
<IfModule mod_rewrite.c>
    Options -MultiViews

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond $1 !^(index\.php|public|css|js|png|jpg|gif|robots\.txt)
    RewriteRule ^(.*)$ index.php/params=$1 [L,QSA]
</IfModule>
```

### CORS notes
To make api available from different domains (e.g. from client app)
the following configuration is needed to be applied for apache:

```
# Always set these headers.
Header always set Access-Control-Allow-Origin "*"
Header always set Access-Control-Allow-Methods "POST, GET, OPTIONS, DELETE, PUT, PATCH"
Header always set Access-Control-Max-Age "1000"
Header always set Access-Control-Allow-Headers "x-requested-with, Content-Type, origin, authorization, accept, client-security-token"

# Added a rewrite to respond with a 204 SUCCESS on every OPTIONS request.
RewriteEngine On
RewriteCond %{REQUEST_METHOD} OPTIONS
RewriteRule ^(.*)$ $1 [R=204,L]
```

Please correct allowed HTTP methods and headers.
 Also for __production__ don't forget to replace the star
 inside ...-Allow-Origin with actual client app domain names.
 This should be done to protect api from the world.
