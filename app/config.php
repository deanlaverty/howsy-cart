<?php

use function DI\create;

use Library\User\Interfaces\UserServiceInterface;
use Library\User\Services\UserService;

// Container bindings
return [
    UserServiceInterface::class => create(UserService::class),
];