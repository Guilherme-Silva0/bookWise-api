<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

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

    public function me(Request $request): object | null
    {
        $user = $request->user();

        if (!$user) {
            return null;
        }

        return $user;
    }
}
