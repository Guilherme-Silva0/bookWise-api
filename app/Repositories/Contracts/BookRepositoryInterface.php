<?php

namespace App\Repositories\Contracts;

interface BookRepositoryInterface
{
    public function getBooks(): ?object;
    public function getBookById(string $id): ?object;
}
