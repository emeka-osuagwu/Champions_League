## Installation

- Download and unzip project.
- Install requirements using composer:
`composer install`
- Change required directory permissions:<br>
`chmod 777 -R storage`<br>
`chmod 777 -R bootstrap/cache`
- Copy `.env.example` to `.env`:<br>
`cp .env.example .env`
- set your database setting in `.env` file:
  - set `DB_CONNECTION` 
  - set `DB_DATABASE` 
  - set `DB_USERNAME` 
  - set `DB_PASSWORD` 
- Run command to create tables: 
`php artisan migrate --seed`
- Open tinker:<br>
`php artisan tinker`
- Run job to setup matches:<br>
`App\Jobs\SetupMatches::dispatchNow();`
- Run server then open browser and click on `Play All` button:
`php artisan serve`
### Enjoy