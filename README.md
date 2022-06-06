#Repo created to host my code for Digit Games test

To run the application you need to have PHP installed in your machine. You can download it here:
https://www.php.net/downloads.php

After installing PHP, we need to install composer to help us manage our dependencies.
https://getcomposer.org/download/

After setting up PHP and composer, we need to install our dependencies
Enter in the folder of the project and run the command:

composer install

If you receive an error like this: "Your requirements could not be resolved to an installable set of packages" Run the following comand to get over this error.

composer install --ignore-platform-reqs

After this, our application is ready to serve us. To start our serve, just run:

php artisan serve

You can check the application in the address: http://localhost:8000/

If you see Laravel's home page, everything is ok.

Also, you can run the tests in the application to verify if everything is working fine.
I am using PHPUnit as test framework. You can use 2 commands to run the tests inside de project folder. Here are the commands:

./vendor/bin/phpunit

php artisan test

To test the application, you can use Postman. Just download it here:
https://www.postman.com/downloads/

In the root of the project, you will find this file:
Game.postman_collection.json

To test the application in postman, open the app and just upload the collection into there.
It's expected that you have access to all my requests and its bodies.

My Redis Databse is hosted in:
https://app.redislabs.com/

With this, the database should be online all the time and you don't need to setup it manually in your machine.
I left the credentials inside the .env.example file.
Just copy it and renove the ".example" part creating the .env file itself.
I've used RedisInsight to connect to my database and test it.
You can download it here:
https://redis.com/redis-enterprise/redis-insight/

and connect to redislab to see the database.
