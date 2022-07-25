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

## Basic usage

You can resolve the cart out of the DI Container, the bindings for the container are stored in app/bootstrap.php

```
$this->container = require app/bootstrap.php';
$cart = $this->container->get(Cart::class);
```

### Create new cart for user
```
$user = new User(id: 1, name: 'John Doe');
$cart = $cart->create($user);
```

### Add item to cart
```
$cart->addItem($productCode);
```

### Get items from cart
```
$cart->getItems();
```

### Get total from cart
```
$cart->getTotal();
```

## Future improvements

- Add PHPStan for static analysis
- Add Makefile to aid with running commands and setup
