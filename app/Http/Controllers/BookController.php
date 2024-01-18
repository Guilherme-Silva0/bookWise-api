<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Response;

class BookController extends Controller
{
    public function __construct(
        private BookService $bookService
    ) {
    }

    public function index()
    {
        return response()->json(['data' => $this->bookService->getBooks()], Response::HTTP_OK);
    }

    public function show(string $id)
    {
        $book = $this->bookService->getBookById($id);

        if (!$book) {
            return response()->json(['data' => []], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $book], Response::HTTP_OK);
    }
}
