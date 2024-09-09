# Restful task management API using Laravel lumen

As per instructions

## Run automated tests

Clone this repo first and then navigate to the project

```cmd
git clone git@github.com:vinmugambi/taski.git
cd taski && composer install
php artisan migrate
./vendor/bin/phpunit --testdox
```

## Run on your machine

```cmd
php artisan db:seed
php -S localhost:8000 -t public
```

Then open http://localhost:8000 in your browser

## Use postgres instead of sqlite

By defualt this project uses sqlite which is great for development and testing.

To use postgres instead copy the contents of `env.postgres` into `.env`

```cmd
cp .env.postgres .env
```

Then change `.env` to match your postgres credentials

```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=taski  # create db first
DB_USERNAME=mugambi # replace with own username
DB_PASSWORD=mugambi # replace with own password
```

create tables, seed data and open server

```cmd
php artisan migrate
php artisan db:seed
php -S localhost:8000 -t public
```
