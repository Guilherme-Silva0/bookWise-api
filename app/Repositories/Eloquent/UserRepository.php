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

    public function update(array $data, string $id): object | null
    {
        $user = $this->model->find($id);

        if (!$user) {
            return null;
        }

        $user->update($data);

        if (!$user) {
            return null;
        }

        return (object) $user;
    }

    public function getByEmail(string $email): ?object
    {
        return (object) $this->model->where('email', $email)->first();
    }

    public function getById(string $id): ?object
    {
        $user = $this->model->find($id);

        if (!$user) {
            return null;
        }

        return (object) $user;
    }
}
