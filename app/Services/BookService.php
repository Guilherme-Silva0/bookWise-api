<?php

namespace App\Services;

use App\Repositories\Contracts\BookRepositoryInterface;

class BookService
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function getBooks(): array
    {
        return $this->bookRepository->getBooks();
    }
}
