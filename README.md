## Project Overview

### Group Members
* Lei Zheng (s3558319)
* Paulo Flores (s3568672)
* Hooi Loong Chua (s3577844)

### Peer Review
Even contribution from all members, 33.3% to each:
* Lei Zheng (s3558319)
    * back-end business logic in Laravel, application controllers and models
* Paulo Zeballos Flores (s3568672)
    * user stories, testing and installation documentation
* Hooi Loong Chua (s3577844)
    * front-end view layer, logging implementation and project documentation

*Tutor: Lawrence Cavedon (Tuesdays, 9:30am - 11:30am)*

### Application Structure
A general overview of the file structure we use.
Not all directories in the source code are listed below, only the ones we've used or modified for our needs.

```
laravel
├── app                   <!-- application layer, business logic -->
│   └── Http              <!-- controllers and middleware -->
├── config                <!-- app configuration -->
├── database              <!-- database migrations and seeder tables -->
├── public                <!-- compiled assets - css, img, js, etc. -->
├── resources
│   └── views             <!-- page templates -->
├── routes
│   └── web.php           <!-- web routes -->
├── storage               <!-- miscellaneous project data -->
│   └── logs              <!-- error logging -->
└── tests
    ├── Browser           <!-- test cases -->
    └── DuskTestCase.php  <!-- Laravel Dusk E2E testing setup -->
```

### Logs
Logging information for errors are captured and time-stamped using Laravel's built-in Monolog library.
The error logs are generated and stored on a day-by-day basis.

These can be found in `deliverables/log_files`

### Deliverables
Deliverables can all be found in the *deliverables* folder in the project's root directory.
These include:
* PDF copy of all our user stories
* PDF copy of all our meeting minutes
* Screenshots of Trello board
* Full activity list of Trello board
* Snapshot of chat logs on our Slack channel
* Tests documentation
* Class diagram
* Database diagram
* Error logging files

### Collaboration Tools
* Trello - https://trello.com/b/8ds7YfU7/part-one (tutor is already invited)
* Slack - https://sept-copsicus.slack.com (anyone with an RMIT email can join)


## PROJECT INSTALLATION
This manual offers a couple of alternatives to set up the project: Docker and Stand-alone version.
Note: commands used in this installation tutorial have been tested in Ubuntu.

## INSTALLING USING DOCKER - LARADOCK

### GETTING BOOKME!
Clone this project's repository from Github.
```
git clone https://github.com/Pancrisp/SEPT-Copsicus.git
```

There are still a few modifications needed to run the docker containers.

Go into laravel folder. Copy and rename .env.example
```cp .env.example .env```

Edit .env so the database host points at 'mysql'
```DB_HOST=mysql```

## SETTING UP TESTING ENVIRONMENT
To run dusk tests with selenium, edit the file 'tests/DuskTestCase.php'
Find and replace the function 'driver()' so it returns a driver pointing at selenium server.
```
    return RemoteWebDriver::create(
	'http://selenium:4444/wd/hub', DesiredCapabilities::chrome()
    );
```

Go into laravel folder. Copy and rename .env
```cp .env .env.dusk.local```

Edit '.env.dusk.local' so the APP_URL points at 'nginx'
```APP_URL=http://nginx```

### DOCKER

### Docker installation
The first step is to install docker. It can be found here, depending on your operative system:
[DOCKER] (https://www.docker.com/get-docker)


### GETTING LARADOCK UP AND RUNNING

### SETTING UP DOCKER

Install docker-composer in case is missing
```sudo apt-get install docker-compose```

Update your user and permissions
Add your user to www-data group and update permissions to grant access
```
sudo usermod -a -G www-data {your_user}
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

Go into laradock folder, then build and run the necessary containers. This might take long specially the first time.
```sudo docker-compose up -d nginx mysql selenium```

### CONFIGURING PROJECT
Enter the Workspace container from laradock folder

```sudo docker-compose exec workspace bash```

Inside this bash, it is possible to run php commands that affect directly our BookMe! project.

Let's update the project dependencies.
```composer update```

Install laravel dusk to run the tests.
```composer require laravel/dusk```

Set up a key
```php artisan key:generate```

Run database migrations and seeding
```
php artisan migrate:reset
php artisan migrate
php artisan db:seed --class=CustomersTableSeeder
php artisan db:seed --class=BusinessesTableSeeder
php artisan db:seed --class=BusinessHoursTableSeeder
php artisan db:seed --class=ActivitiesTableSeeder
php artisan db:seed --class=EmployeesTableSeeder
php artisan db:seed --class=RostersTableSeeder
php artisan db:seed --class=BookingsTableSeeder
```

### TESTS
To run the tests go into the workspace container bash and run:
```php artisan dusk```

In this ocasion there won't be any Gui for the tests since it is running in the selenium container.

### ENJOY
Visit localhost in your web browser and our BookeMe app should be running.


## STANDALONE INSTALLATION
To run the project there are a few dependencies that need to be installed:
* PHP
* MYSQL
* LARAVEL

### INSTALLING PHP
This project requires PHP version 7

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

#### Running the migrations
Execute the following commands in the laravel folder of the cloned project. There is included some dummy data in the seeders for testing purposes.
```
php artisan migrate:reset
php artisan migrate
php artisan db:seed --class=CustomersTableSeeder
php artisan db:seed --class=BusinessesTableSeeder
php artisan db:seed --class=ActivitiesTableSeeder
php artisan db:seed --class=EmployeesTableSeeder
php artisan db:seed --class=RostersTableSeeder
php artisan db:seed --class=BookingsTableSeeder
```

In case some class not found errors arise after executing the seeding, please run:
`composer dump-autoload` and rerun the seeder commands.

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
