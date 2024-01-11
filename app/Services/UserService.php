<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Jobs\SendEmailRestorePassword;
use App\Jobs\SendEmailVerification;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function confirmEmail(string $confirmationToken, string $userId): bool
    {
        $expectedConfirmationToken = hash_hmac('sha256', $userId, env('SECRET_KEY'));

        if ($confirmationToken === $expectedConfirmationToken) {
            if($this->userRepository->update(['email_verified_at' => now()], $userId)) {
                return true;
            }
            return false;
        }

        return false;
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

    public function forgotPassword(string $email): bool
    {
        $user = $this->userRepository->getByEmail($email);

        if(!$user) {
            return false;
        }

        if(!$user->hasVerifiedEmail()) {
            return false;
        }

        dispatch(new SendEmailRestorePassword($user, app()->getLocale()));

        return true;
    }

    public function resetPassword(string $password, string $token, string $id): object | bool
    {
        $user = $this->userRepository->getById($id);

        if(!$user) {
            return false;
        }

        if(!$user->hasVerifiedEmail()) {
            return false;
        }

        $expectedToken = hash_hmac('sha256', $user->email, env('SECRET_KEY'));

        if ($token !== $expectedToken) {
            return false;
        }

        return (object) $this->userRepository->update(['password' => Hash::make($password)], $id);
    }
}
