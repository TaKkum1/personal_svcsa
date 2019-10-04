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
#### Git repo
We have these branches reserved. Please make sure you branch is up to date before pushing.

- Master ------> Production codes
- Beta   ------> Beta codes
- Any other branches your need 

*Please use `git pull --rebase` to rebase your change based on latest changes*

### Basic work flow
```
|
|-> create your branch from latest master branch, work on anything you want, and OF COURSE test it!
|
|-> commit your changes in your branch and push.
|
|-> open a pull request from your branch to branch `beta`.
|
|-> if you are sure, merge your change to `beta` through pull request
|
|-> MUST DO: verify your every single change on `beta` 
|
|-> open another pull request and notify other collaborators for review
|
|-> if everything is good from review, merge the pull request
|
|-> verify changes in production sites
|
|-> DONT FORGET: create a release tag in GitHub :)

```
### Commit codes

First of First, **PLEASE DON'T FORCE PUSH OR PUSH CODES TO `master` DIRECTLY**. This will also be forced by git branch protection. :) 
Please open a pull request to avoid any potential conflicts.
 
  
### Deployment
There are two environment setup: `prod` and `beta`. This application is using `buddy.works` to do the deployment.
```
www.svcsa.org
```
```
beta.svcsa.org
```
Beta is staging environment to push the **ANY** codes for verification before pushing to production

This endpoint is running **Isolatedly** with production, which means separate php codes. 
> Since this website is heavy read, light write to database, database for both beta and prod is using production endpoint. **PLEASE, PLEASE, PLEASE** make sure you understand this and **DON'T** make write request in BETA

*TODO: This should be forced with different DB username and access*

#### Deploy to BETA
Only thing you need to do to deploy to beta is to 
```
push your change to beta branch on Git
```

#### Deploy to production
In order to manage distribution better, direct push to FTP service will be deprecated. Please don't do that and follow the steps here.

0. Make your codes ready
   - Tested properly
   - Rebased with latest `master` branch
   - Better to squash your changes into one commit
   - Provide good commit information about your changes 
1. Open an pull request with your latest changes
   - With necessary information about what are your changes
   - Ask someone to review    
2. Merge your change, wait and have fun!
   - In shadow, one new tag is created in Github release for version management. 
   - If anything is not working as expected, revert from Github release, open a pull request with revert changes. Get it merged


#### Nginx management

#### MySQL management


## Pending Issue
1.  `public_assets` to `url_domain_root`
`public_assets` is misused as `url_domain_root` in building url.
We should use `url_domain_root` to build relative url and use `public_assets` for asset urls.
2. Image size optimization
3. Image lazy load
4. Font file loading error

## Data backup and recovery