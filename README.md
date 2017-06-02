# Installation
## 1. Défine your parameters
edit this file `app/config/parameters.yml`

## 2. Download vendors
with Composer:

    php composer.phar install

## 3. Create the database:

    php bin/console doctrine:database:create

then create the table :

    php bin/console doctrine:schema:update --dump-sql
    php bin/console doctrine:schema:update --force

fill the data base with the SQL script

    php bin/console doctrine:fixtures:load

## 4. How to configure virtual hosts on your localhost
### 1. we will create a virtual host under the name: "snowtricks.dev"
- in the repository *C:\Windows\System32\drivers\etc*; open “hosts” file with admin privileges and add the following to its end;
127.0.0.1 *snowtricks.dev* 
### 2.  allow virtual hosts in httpd.conf  
- ckick on wamp tray icon and Apache->httpd.conf  
-search for *# Include conf/extra/httpd-vhosts.conf* and comment it out (by deleting the # caracter): *Include conf/extra/httpd-vhosts.conf*  
- then at the bottom of the file add the *myBlog* project like this:  
```
    <VirtualHost *:80>
	    ServerName myblog.dev
	    DocumentRoot C:/web/snowtricks/web
	    <Directory  "C:/web/snowtricks/web/">
    		Options +Indexes +Includes +FollowSymLinks +MultiViews
	    	AllowOverride All
		    Require local
	    </Directory>
    </VirtualHost>
```
- and restart the wamp services
