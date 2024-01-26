<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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
                    'image_path',
                    'availability',
                    'user' => [
                        'id',
                        'name',
                        'first_name',
                        'last_name',
                        'email',
                        'profile_image',
                        'profile_info',
                        'email_verified_at',
                        'user_type',
                        'status',
                    ],
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

        $user = User::find($book->user_id);

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
                'image_path',
                'availability',
                'user' => [
                    'id',
                    'name',
                    'first_name',
                    'last_name',
                    'email',
                    'profile_image',
                    'profile_info',
                    'email_verified_at',
                    'user_type',
                    'status',
                ],
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
                'image_path' => $book->image_path,
                'availability' => $book->availability ? 'Disponível' : 'Indisponível',
                'user' => [
                    'id' => $user->id,
                    'name' => "{$user->first_name} {$user->last_name}",
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'profile_image' => $user->profile_image,
                    'profile_info' => $user->profile_info,
                    'email_verified_at' => $user->email_verified_at,
                    'user_type' => 'normal',
                    'status' => 'active',
                ],
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
                'image_path',
                'availability',
                'user',
            ],
        ]);
    }

    public function test_can_create_book(): void
    {
        $book = Book::factory()->makeOne();

        $user = User::factory()->create();

        $response = $this->postJson('/api/books?lang=pt_BR', $book->toArray(), [
            'Authorization' => 'Bearer ' . $user->createToken('authToken')->plainTextToken,
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
                'publication_year' => (int) $book->publication_year,
                'language' => $book->language,
                'page_count' => $book->page_count,
                'publisher' => $book->publisher,
                'image_path' => $book->image_path,
                'availability' => $book->availability ? 'Disponível' : 'Indisponível',
                'user' => [
                    'id' => $user->id,
                    'name' => "{$user->first_name} {$user->last_name}",
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'profile_image' => $user->profile_image,
                    'profile_info' => $user->profile_info,
                    'email_verified_at' => $user->email_verified_at,
                    'user_type' => 'normal',
                    'status' => 'active',
                ],
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

    public function test_can_create_book_empty_data(): void
    {
        $token = User::factory()->create()->createToken('authToken')->plainTextToken;

        $response = $this->postJson('/api/books?lang=pt_BR', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'O campo título é obrigatório. (e mais 8 erros)',
            'errors' => [
                'title' => [
                    0 => 'O campo título é obrigatório.',
                ],
                'description' => [
                    0 => 'O campo descrição é obrigatório.',
                ],
                'author' => [
                    0 => 'O campo author é obrigatório.',
                ],
                'price' => [
                    0 => 'O campo price é obrigatório.',
                ],
                'genre' => [
                    0 => 'O campo genre é obrigatório.',
                ],
                'publication_year' => [
                    0 => 'O campo publication year é obrigatório.',
                ],
                'language' => [
                    0 => 'O campo language é obrigatório.',
                ],
                'page_count' => [
                    0 => 'O campo page count é obrigatório.',
                ],
                'image_path' => [
                    0 => 'O campo image path é obrigatório.',
                ],
            ],
        ]);
    }

    public function test_can_create_book_invalid_data(): void
    {
        $token = User::factory()->create()->createToken('authToken')->plainTextToken;

        $response = $this->postJson('/api/books?lang=pt_BR', [
            'title' => 'se',
            'description' => 'sesese',
            'author' => 'se',
            'price' => 'se',
            'condition' => 'se',
            'genre' => 'se',
            'isbn' => 887699868,
            'publication_year' => ((int) date('Y')) + 1,
            'language' => str_repeat('x', 256),
            'page_count' => 'se',
            'publisher' => 'se',
            'image_path' => 'se',
            'availability' => 'se',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->whereAll([
                'message' => 'O campo título deve ter pelo menos 3 caracteres. (e mais 12 erros)',
                'errors.title.0' => 'O campo título deve ter pelo menos 3 caracteres.',
                'errors.description.0' => 'O campo descrição deve ter pelo menos 10 caracteres.',
                'errors.author.0' => 'O campo author deve ter pelo menos 3 caracteres.',
                'errors.price.0' => 'O campo price deve ser um número.',
                'errors.condition.0' => 'O campo condition selecionado é inválido.',
                'errors.genre.0' => 'O campo genre deve ter pelo menos 3 caracteres.',
                'errors.isbn.0' => 'O campo isbn tem um formato inválido.',
                'errors.publication_year.0' => 'O campo publication year não pode ser superior a 2024.',
                'errors.language.0' => 'O campo language não pode ser superior a 255 caracteres.',
                'errors.page_count.0' => 'O campo page count deve ser um número.',
                'errors.publisher.0' => 'O campo publisher deve ter pelo menos 3 caracteres.',
                'errors.image_path.0' => 'O campo image path deve ter pelo menos 3 caracteres.',
                'errors.availability.0' => 'O campo availability deve ser verdadeiro ou falso.',
            ]);
        });
    }
}
