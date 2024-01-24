<?php

namespace App\Services;

use App\DTOs\Book\CreateBookDTO;
use App\Repositories\Contracts\BookRepositoryInterface;

class BookService
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function getBooks(): ?object
    {
        return $this->bookRepository->getBooks();
    }

    public function getBookById(string $id): ?object
    {
        if (!$id) {
            return null;
        }

        return $this->bookRepository->getBookById($id);
    }

    public function create(CreateBookDTO $createBookDTO): ?object
    {
        return $this->bookRepository->createBook($createBookDTO);
    }
}
