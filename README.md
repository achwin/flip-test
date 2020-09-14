# Flip-Test
Flip test simple disbursement service 

## Feature
- Disbursement Feature
- Progress status tracking using Jobs / Worker

## Installation
- Clone this repo or extract the zip
```
# move to directory
cd flip-test

# install dependency
composer install

# copy env then please edit .env to set your database config
cp .env.example .env

# generate key for laravel
php artisan key:generate

# migrate database
php artisan migrate

# seed the data
php artisan db:seed

# instal fe dependency and build asset
npm install && npm run dev
```

## Run 
You need to run php server and php queue worker
```
# run php server
php artisan serve --port=8000

# run php worker
php artisan queue:work
``` 

you can login with
```
email: 
flip@gmail.com
password: 
password
```

## Testing
To run testing please make new db for test db and set configuration on `.env.testing`
```
# run db migrate for testing
php artisan migrate --env=testing

# run test
vendor/bin/phpunit 
``` 
