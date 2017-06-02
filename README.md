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
