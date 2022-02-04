# A Fullstack Commerce Web App

[Vue.js](https://vuejs.org/) in the front, [Laravel PHP framework](https://laravel.com/) in the back.

## Features

* Full CRUD functionality
* MVC architecture so decoupled you can make API calls with your eyes closed
* Commented code with no profanities
* Good source of vitamin C [^1]

[^1]: This statement has not been evaluated by the FDA.

## Installation

Clone the repository

    git clone git@github.com:CarterTMDev/commerce-laravel-vue.git

Switch to the repo folder

    cd commerce-laravel-vue
    
Copy the .env.example file, rename as `.env`, and edit the environment variables as needed (such as the SQL credentials).

    cp .env.example .env

Run Sail (Info: https://laravel.com/docs/8.x/sail)

    ./vendor/bin/sail up -d

Install dependencies

    ./vendor/bin/sail composer install
    ./vendor/bin/sail npm install

Create the database

    ./vendor/bin/sail php artisan migrate

(Optional) Fill the database with dummy data

    ./vendor/bin/sail php artisan db:seed

## License

Open an issue and send me a link to a cool GitHub repository. If it's cool enough, I'll let you use this for whatever you want.

Or if you're lame you can just use the [MIT](https://choosealicense.com/licenses/mit/) license.
