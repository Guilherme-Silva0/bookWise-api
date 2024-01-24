<?php

namespace App\Repositories\Contracts;

use App\DTOs\Book\CreateBookDTO;

interface BookRepositoryInterface
{
    public function getBooks(): ?object;
    public function getBookById(string $id): ?object;
    public function createBook(CreateBookDTO $createBookDTO): ?object;
}
