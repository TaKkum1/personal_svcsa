# SVCSA Web
> Website for SVCSA organization

## Web Stack
* PHP
    * thinkPHP
* MYSQL
* NGINX

## Installation for development

### Install PHP
[Download PHP](https://www.php.net/downloads.php)
For development, highly recommend:
* MAMP for mac [here](https://www.mamp.info/en/)
* WAMP for windows [here](http://www.wampserver.com/en/)

These two sets up local environment without any manual config!

### Install composer
This repo requires composer to manage dependencies. For more information, please check [Composer](https://getcomposer.org/) here.

### Install composer
```sh
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Then 

```sh
php composer.phar install
```

One `composer.phar` will be generated on root. No need to commit this file

### Start Dev server
Start the server app that will serve the php application. Like Apache or nginx

### Migirate database
There would be multiple ways to manage your local mySQL server. One easy way will be 
1. Go to phpMyAdmin
2. Import database from database copy

### Connect application with local database
We need to change the database config to connect dev server to local mySQL instance with username and password. 

Config file will be in

```
application/database.php
```

There is an example config example in
```
application/database-example.php
```

#### EVERYTHING IS READY! GO TO YOUR LOCAL SERVER

## Development

There are some few points you need to know when onboarding the application
#### Check in codes
#### Development environment
There are two environment setup: `prod` and `beta`
```
www.svcsa.org
```
```
beta.svcsa.org
```
This is staging environment to push the latest codes for verification before pushing to production

This endpoint is running **isolatedly** with production, which means separate php codes. (Since website is readonly to database, database is using production data)
For more info about how to sync database db from `prod` to `beta`, please checkout section below
#### Version control

#### Nginx management

#### MySQL management

#### Database sync up

## Deployment
TBA, describe how to release the changes to cloud

## Pending Issue
TBA, project target

## Data backup and recovery