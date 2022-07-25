<?php

use Library\Cart\Cart;

use Library\Product\Interfaces\ProductServiceInterface;
use Library\Product\Services\ProductService;
use Library\User\Interfaces\UserServiceInterface;
use Library\User\Services\UserService;
use Psr\Container\ContainerInterface;
use function DI\create;

// Container bindings
return [
    UserServiceInterface::class => create(UserService::class),
    ProductServiceInterface::class => create(ProductService::class),
    Cart::class => function (ContainerInterface $c) {
        return new Cart(
            $c->get(UserServiceInterface::class),
            $c->get(ProductServiceInterface::class)
        );
    },
];
