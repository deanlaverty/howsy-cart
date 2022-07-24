<?php

declare(strict_types=1);

namespace Library\User\Services;

use Library\User\Interfaces\UserServiceInterface;
use Library\User\User;

final class UserService implements UserServiceInterface
{
    public function hasAgreedToContract(User $user): bool
    {
        return false;
    }
}
