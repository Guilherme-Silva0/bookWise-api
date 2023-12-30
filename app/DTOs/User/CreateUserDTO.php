<?php

namespace App\DTOs\User;

use App\Http\Requests\Api\UserRegisterRequest;
use Illuminate\Support\Facades\Hash;

class CreateUserDTO
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $password,
    ) {
    }

    public static function makeFromRequest(UserRegisterRequest $request): self
    {
        return new self(
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('email'),
            Hash::make($request->get('password')),
        );
    }
}
