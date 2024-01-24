<?php

namespace App\Http\Controllers;

use App\DTOs\Book\CreateBookDTO;
use App\Http\Resources\BookResource;
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
        $books = $this->bookService->getBooks();

        return (BookResource::collection($books))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function show(string $id)
    {
        $book = $this->bookService->getBookById($id);

        if (!$book) {
            return response()->json(['data' => []], Response::HTTP_NOT_FOUND);
        }

        return (new BookResource($book))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $book = $this->bookService->create(CreateBookDTO::makeFromRequest($request));

        return (new BookResource($book))->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
