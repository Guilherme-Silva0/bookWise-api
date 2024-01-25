<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_books(): void
    {
        Book::factory(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'author',
                    'description',
                    'price',
                    'condition',
                    'genre',
                    'isbn',
                    'publication_year',
                    'language',
                    'page_count',
                    'publisher',
                    'added_date',
                    'image_path',
                    'availability',
                ],
            ],
        ]);
    }

    public function test_can_list_books_empty(): void
    {
        $response = $this->getJson('/api/books');

        $response->assertStatus(200);

        $response->assertJsonCount(0, 'data');
    }

    public function test_can_show_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}?lang=pt_BR");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'author',
                'description',
                'price',
                'condition',
                'genre',
                'isbn',
                'publication_year',
                'language',
                'page_count',
                'publisher',
                'added_date',
                'image_path',
                'availability',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'price' => 'R$ ' . number_format($book->price, 2, ',', '.'),
                'condition' => $book->condition,
                'genre' => $book->genre,
                'isbn' => $book->isbn,
                'publication_year' => $book->publication_year,
                'language' => $book->language,
                'page_count' => $book->page_count,
                'publisher' => $book->publisher,
                'added_date' => Carbon::parse($book->added_date)->format('Y-m-d H:i:s'),
                'image_path' => $book->image_path,
                'availability' => $book->availability ? 'Disponível' : 'Indisponível',
            ],
        ]);
    }

    public function test_can_show_book_invalid_id(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson('/api/books/999999');

        $response->assertStatus(404);

        $response->assertJsonMissing([
            'data' => [
                'id',
                'title',
                'author',
                'description',
                'price',
                'condition',
                'genre',
                'isbn',
                'publication_year',
                'language',
                'page_count',
                'publisher',
                'added_date',
                'image_path',
                'availability',
            ],
        ]);
    }

    public function test_can_create_book(): void
    {
        $book = Book::factory()->makeOne();

        $token = User::factory()->create()->createToken('authToken')->plainTextToken;

        $response = $this->postJson('/api/books?lang=pt_BR', $book->toArray(), [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'price' => 'R$ ' . number_format($book->price, 2, ',', '.'),
                'condition' => $book->condition,
                'genre' => $book->genre,
                'isbn' => $book->isbn,
                'publication_year' => (float) $book->publication_year,
                'language' => $book->language,
                'page_count' => $book->page_count,
                'publisher' => $book->publisher,
                'added_date' => Carbon::parse($book->added_date)->format('Y-m-d H:i:s'),
                'image_path' => $book->image_path,
                'availability' => $book->availability ? 'Disponível' : 'Indisponível',
            ],
        ]);
    }

    public function test_can_create_book_invalid_token(): void
    {
        $book = Book::factory()->makeOne();

        $response = $this->postJson('/api/books?lang=pt_BR', $book->toArray());

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
