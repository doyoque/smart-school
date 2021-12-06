# Smart School Repository

A simple Software as a service (SaaS) with Laravel framework and vuejs.

## Prerequisite

-   PHP 8.0
-   MySQL 5.7.22
-   Nodejs 14.16

## Run locally (without Docker)

clone this repository.\
Copy `.env.example` to `.env` and configure these value for database connection.

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_db
DB_USERNAME=root
DB_PASSWORD=secret123
```

Run local server.

```bash
php artisan serve
```

Install node dependency with `npm install` and then run `npm run watch` for live reload.

## Run as container (with Docker)

For development environment I recommend to use Docker. Utilize the `stack.sh` for common use.

```bash
./stack.sh up # For spin up the services (nginx, mysql, and php)
./stack.sh down # For tear down the services
./stack.sh set-env # For auto generate .env (for docker only)
./stack.sh install-deps <node|composer> # If no argument provided it will install both node and composer dependencies
```
