<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function login(Request $request): object | null
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return $request->user();
        }

        return null;
    }

    public function logout(Request $request): bool
    {
        if($request->user()->currentAccessToken()->delete()) {
            return true;
        }

        return false;
    }
}
