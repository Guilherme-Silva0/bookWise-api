<?php

namespace Tests\Feature\Api;

use App\Models\Book;
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
}
