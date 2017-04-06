# SEPT-Copsicus

## Group Members

Lei Zheng (s3558319)

Paulo Flores (s3568672)

Hooi Loong Chua (s3577844)


## INSTALLATION 

Note: commands run in this installation tutorial have been tested in Ubuntu 16.04 LTS
To run the project there are a few installation requirements that need to be met:
* PHP
* MYSQL
* LARAVEL

### INSTALLING PHP
This project uses PHP version 7

Updating package manager.	
`sudo apt-get update`

Installing php 7
`sudo apt-get install php`

Installing extensions required by Laravel
`sudo apt-get install php-common php-mbstring php-xml php-cli`


### INSTALLING LARAVEL
Laravel requires the installation of ‘composer’

Installing requirements for composer
`sudo apt-get install curl git`

Downloading and installing composer
`curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer`

Testing composer successful installation 
`composer`
 
Download Laravel installer using composer
`composer global require "laravel/installer"`

Add bin folder into $PATH, so the Laravel executable can be located by the system.  One of the ways of doing it is:

Open ~/.bashrc
Add this command at the end of the file:
`export PATH=$PATH:~/.composer/vendor/bin`

### MYSQL DATABASE INSTALLATION
Once Laravel is ready, we proceed to install and configure a database. By default, this project uses MySQL database.

#### Install mysql database
`sudo apt-get install mysql-server`

Set a password for the mysql root user. This step will be necessary for later database setup.

Create Project database, username and password 
Login to MySQL
`mysql -u mysql_user -p`

Enter password from the step above. Once entered the valid password a ‘mysql > ’ prompt appears.

Create the database for the project
`create database SEPT;`

Create a user
`create user sept;`

Grant privileges to ‘sept’ user and assigning password ‘secret’
`grant all on SEPT.* to 'sept'@'localhost' identified by 'secret';`

`quit` to exit mysql >

#### Installing database driver
`sudo apt-get install php-mysql`


### PROJECT SET UP

Clone this repository.

Open laravel folder in the cloned project and setup project environment.
`cd …/laravel`

Update composer dependencies
`composer update`

Set up environment for database connection. 
Copy and rename .env.example
`cp .env.example .env`

Edit .env and set up parameters as shown below
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=SEPT
DB_USERNAME=sept
DB_PASSWORD=secret
```
	
Set up a key
`php artisan key:generate`

Configuring Database In Laravel

Edit /config/database.php
Update the values for mysql db to match the ones already configured in the environment.
```
'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'SEPT'),
            'username' => env('DB_USERNAME', 'sept'),
            'password' => env('DB_PASSWORD', 'secret'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
```

### SETUP TESTING WITH DUSK
To run the browser automated tests, dusk utilises Chrome Driver which is a server that implements WebDriver protocol. It works with Chrome or Chromium browser. 

#### Installation requirements

Installing some dependencies
`sudo apt-get install php-curl`

Update .env located in laravel folder to include APP_URL.
Since the tests are run locally serves by php, it is specified APP_URL as localhost. It is important to match the port as well. If you decide to serve on a different port, this value should be updated here. 

`APP_URL = localhost:8000`

#### Installing Chrome
Make sure chrome is in the default location so later chrome driver can locate it.

#### Installing Chrome Driver
Download chrome driver for Linux from 
[Chrome Driver](https://chromedriver.storage.googleapis.com/index.html?path=2.29/)

Unzip the file in and take note of the folder in which you place it. This folder must be included in the $PATH. One of the ways of doing it is:

Open ~/.bashrc
Add this command at the end of the file:
`export PATH=$PATH:/path/to/your/chrome/driver/folder`

Testing chromedriver
Running chromedriver in the terminal should have a similar outcome as below.
```
Starting ChromeDriver 2.29.461599 (8cfe70b3bca63aec509c39bf48c384e7eba3372b) on port 9515
Only local connections are allowed.
```

### RUNNING THE PROJECT

To run the project

`php artisan serve --port=8000`

Open a browser and visit `localhost:8000` and enjoy!!


#### RUN TESTS
To run the tests first start the server
`php artisan serve --port=8000`
Open a different terminal and run
`php artisan dusk`

In case all the tests return Error. Open a different terminal and start manually start chrome driver 
`chromedriver`

Then run again 
`php artisan dusk`

