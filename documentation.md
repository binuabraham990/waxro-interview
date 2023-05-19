### Working of the application

- Clone/download and run composer install
- Import database tables from localhost.sql to the local database.
- copy .env.example to .env and make database changes
- php artisan key:generate may be we need to run the key generation also
- Using mysql and php 8.1
- For easiness download the database backup file and import to local db
- Copy the application to local server and run it using 'php artisan serve' command.
- Consider properly changing .env values as per the mysql credentials

- Working is straight forward.-
-> Create a user using the /register url
-> Login using /login
-> Deposit an amount using /deposit
-> Withdraw amount usint /withdraw
-> Transfer amoubt using /transfer
-> See statement using /statement
-> /dashboard will provide the balance of logged in account
