<?php

use function DI\create;

use Library\Interfaces\Repositories\InterestAccountRepositoryInterface;
use Library\Repositories\InterestAccountRepository;
use Library\Interfaces\Services\InterestAccountServiceInterface;
use Library\Services\InterestAccountService;
use Library\Classes\UserStatsApi;

// Container bindings
return [
//    InterestAccountRepositoryInterface::class => create(InterestAccountRepository::class),
//    InterestAccountServiceInterface::class => function(\Psr\Container\ContainerInterface $c) {
//        return new InterestAccountService($c->get(InterestAccountRepositoryInterface::class), new UserStatsApi());
//    },
];