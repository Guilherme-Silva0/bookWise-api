<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Jobs\SendEmailVerification;
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
        $user = $this->userRepository->create($dto);

        if (!$user) {
            return null;
        }

        dispatch(new SendEmailVerification($user, app()->getLocale()));

        return $user;
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

    public function update(UpdateUserDTO $dto, string $id): object | null
    {
        // Remove null values
        $filteredData = collect($dto->toArray())
            ->filter(function ($value) {
                return $value !== null;
            })->toArray();

        if (empty($filteredData)) {
            return null;
        }

        return $this->userRepository->update($filteredData, $id);
    }
}
