<?php

declare(strict_types=1);

namespace Library\User\Interfaces;

use Library\User\User;

interface UserServiceInterface
{
    public function hasAgreedToContract(User $user): bool;
}
