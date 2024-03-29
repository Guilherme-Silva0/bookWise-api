<?php

namespace App\Repositories\Contracts;

use App\DTOs\User\CreateUserDTO;

interface UserRepositoryInterface
{
    public function create(CreateUserDTO $dto): object | null;
    public function update(array $data, string $id): object | null;
    public function getByEmail(string $email): object | null;
    public function getById(string $id): object | null;
}
