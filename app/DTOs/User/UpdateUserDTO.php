<?php

namespace App\DTOs\User;

use App\Http\Requests\Api\UserUpdateRequest;

class UpdateUserDTO
{
    public function __construct(
        public ?string $first_name,
        public ?string $last_name,
        public ?string $email,
        public ?string $profile_image,
        public ?string $profile_info,
        public ?string $status,
        public ?string $user_type
    ) {
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'profile_info' => $this->profile_info,
            'status' => $this->status,
            'user_type' => $this->user_type
        ];
    }

    public static function makeFromRequest(UserUpdateRequest $request): self
    {
        return new self(
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('email'),
            $request->get('profile_image'),
            $request->get('profile_info'),
            $request->get('status'),
            $request->get('user_type')
        );
    }
}
