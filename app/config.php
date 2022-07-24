<?php

use function DI\create;

use Library\User\Interfaces\UserServiceInterface;
use Library\User\Services\UserService;
use Library\Product\Interfaces\ProductServiceInterface;
use Library\Product\Services\ProductService;

// Container bindings
return [
    UserServiceInterface::class => create(UserService::class),
    ProductServiceInterface::class => create(ProductService::class)
];