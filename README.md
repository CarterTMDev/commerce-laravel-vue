## Installation

Clone the repository

    git clone git@github.com:CarterTMDev/commerce-laravel-vue.git

Switch to the repo folder

    cd commerce-laravel-vue

Run Sail (Info: https://laravel.com/docs/8.x/sail)

    ./vendor/bin/sail up -d

Install dependencies

    ./vendor/bin/sail composer install
    ./vendor/bin/sail npm install

Create the database

    ./vendor/bin/sail php artisan migrate

(Optional) Seed the database

    ./vendor/bin/sail php artisan db:seed