
## Basic Authentication Service and Unit Test.

## Authentication by Passport.

Create .env file on the root directory and copy the content of .env.example to it.

Run `php artisan key:generate` on your terminal to generate your key in .env file.
Run `composer install` or `composer update` on your terminal to install the dependencies.

### Php version 7.1.3 while laravel version is 7.0.*

### You need to have [composer](https://getcomposer.org/), [php](https://www.php.net/) and [laravel](https://laravel.com/docs)

## Database
-- Enter your database name and credentials in your .env file
-- Run the command `php artisan migrate` in your root directory.
-- Run the command `php artisan passport:install` in your root directory.

### If using xampp, you'll need to run another instance of the service 
 -- Run `php artisan serve --port 8001` on the terminal 
 -- Copy the generated url into `MAIN_URL` of .env file.
 -- `MAIN_URL` becomes `MAIN_URL="http://127.0.0.1:8001"` if the url generated is `http://127.0.0.1:8001`.

 ### If using ngix, 
 -- Copy the url generated when you run `php artisan serve` into `MAIN_URL` of .env file.

 ## Run Test
 Run the command `vendor/bin/phpunit` in your root directory

 ## Start Project
 -- Run the command `php artisan serve` in your root directory when all is set.

API Documentation is found on "https://documenter.getpostman.com/view/2571533/TVKEYHY6"
