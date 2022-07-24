# Howsy Basket Test

#### Cart library built on Docker using PHP 8.1 and nginx. It was built without a frontend and runs of PHPUnit testing using Mockery. 

## Setup

First we need to build the docker image and start the containers.
```
docker-compose up -d --build
```

We then need to run composer install

```
docker-compose exec app composer install
```

## Running Tests

```
docker-compose exec app ./vendor/bin/phpunit
```

## Running CS check

```
docker-compose exec app ./vendor/bin/ecs check .
```
