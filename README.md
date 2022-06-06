Repo created to host my code for Digit Games test

To run the application you need to have PHP installed in your machine. You can download it here: https://www.php.net/downloads.php After installing PHP, we need to install composer to help us ith our dependencies. https://getcomposer.org/download/

After setting up PHP and composer, we need to install our dependencies Enter in the folder of the project and run the command:

composer install

If you receive an error like this: "Your requirements could not be resolved to an installable set of packages" Run the following comand to get over this error.

composer install --ignore-platform-reqs

After this, our application is ready to serve us. To start our serve, just run:

php artisan serve

You check the application in the address: http://localhost:8000/

If you see Laravel's home page, everything is ok.

Also, you can run the tests in the application to verify if everything is working fine.
I am using PHPUnit for the test framework. You can use 2 commands to run the tests inside de project folder. Here are the commands:

./vendor/bin/phpunit

php artisan test



To test the application, you can use Postman. You can download it here: https://www.postman.com/downloads/
