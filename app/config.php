<?php

use Library\Product\Interfaces\ProductServiceInterface;

use Library\Product\Services\ProductService;
use Library\User\Interfaces\UserServiceInterface;
use Library\User\Services\UserService;
use function DI\create;

// Container bindings
return [
    UserServiceInterface::class => create(UserService::class),
    ProductServiceInterface::class => create(ProductService::class)
];
