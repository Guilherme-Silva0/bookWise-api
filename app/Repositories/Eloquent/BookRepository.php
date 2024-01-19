<?php

namespace App\Repositories\Eloquent;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function __construct(
        protected Book $model
    ) {
    }

    public function getBooks(): ?object
    {
        return (object) $this->model->all();
    }

    public function getBookById(string $id): ?object
    {
        return $this->model->find($id);
    }
}
