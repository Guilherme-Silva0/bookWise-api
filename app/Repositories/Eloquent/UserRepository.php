<?php

namespace App\Repositories\Eloquent;

use App\DTOs\User\CreateUserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {
    }

    public function create(CreateUserDTO $dto): object | null
    {
        $user = $this->model->create((array) $dto);

        if (!$user) {
            return null;
        }

        return (object) $user;
    }
}
