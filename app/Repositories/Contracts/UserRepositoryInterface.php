<?php

namespace App\Repositories\Contracts;

use App\DTOs\User\CreateUserDTO;

interface UserRepositoryInterface
{
    public function create(CreateUserDTO $dto): object | null;
}
