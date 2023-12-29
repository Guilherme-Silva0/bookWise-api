<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function create(CreateUserDTO $dto): object | null
    {
        return $this->userRepository->create($dto);
    }
}
