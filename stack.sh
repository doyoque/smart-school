#!/bin/bash

REDCOLOR="\e[31m"
GREENCOLOR="\e[32m"
YELLOWCOLOR="\e[33m"
ENDCOLOR="\e[0m"

clear
declare options=$@
declare optionsArr=($options)

set_dotenv () {
    rm -rf .env
    cp .env.example .env
    sed -i -e 's/MIX_PORT=8000/MIX_PORT=8787/g' .env
    sed -i -e 's/DB_HOST=127.0.0.1/DB_HOST=school-db/g' .env
    sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=secret/g' .env
}

install_composer () {
    docker run --rm --volume $(pwd):/app --user $(id -u):$(id -g) composer:2.1.3 install
}

install_node () {
    docker run --rm -v $(pwd):/usr/src/app -w /usr/src/app node:14.16-alpine npm install
}

laravel_common_init () {
    docker-compose up --build -d
    docker-compose exec school-app composer dump-autoload
    docker-compose exec school-app php artisan key:generate
}

laravel_clear_config () {
    docker-compose exec school-app php artisan config:clear && php artisan cache:clear && php artisan route:clear
}

if [[ "${optionsArr[0]}" == "set-env" ]]
then
    set_dotenv
fi

if [[ "${optionsArr[0]}" == "install-deps" ]]
then
    if [[ "${optionsArr[1]}" == "node" ]]
    then
		rm -rf node_modules/
		echo -e "${YELLOWCOLOR}Installing dependency for node packages${ENDCOLOR}"
        install_node
	elif [[ "${optionsArr[1]}" == "composer" ]]
    then
		echo -e "${YELLOWCOLOR}Installing dependency for laravel${ENDCOLOR}"
        install_composer
    else
        rm -rf node_modules/ vendor/
		echo -e "${YELLOWCOLOR}Installing dependency for node packages${ENDCOLOR}"
        install_node
		echo -e "${YELLOWCOLOR}Installing dependency for laravel${ENDCOLOR}"
        install_composer
    fi
fi

if [[ "${optionsArr[0]}" == "up" ]]
then
    if [ ! -d "$(pwd)/vendor" ]
    then
        install_composer
    fi

    if [ ! -d "$(pwd)/node_modules" ]
    then
        install_node
    fi
    laravel_common_init
    docker-compose exec school-app php artisan migrate:refresh --seed
    docker-compose exec php artisan passport:install --force
    npm run watch
fi

if [[ "${optionsArr[0]}" == "down" ]]
then
    docker-compose down --remove-orphans
fi
