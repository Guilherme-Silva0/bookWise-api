<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
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
}
