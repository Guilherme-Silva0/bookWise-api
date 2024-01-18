<?php

namespace App\Repositories\Contracts;

interface BookRepositoryInterface
{
    public function getBooks(): array;
    public function getBookById(string $id): ?object;
}
