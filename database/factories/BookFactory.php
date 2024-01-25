<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isbn = $this->faker->isbn13();

        $isbnWithDashes = substr($isbn, 0, 3) . '-' . substr($isbn, 3, 1) . '-' . substr($isbn, 4, 4) . '-' . substr($isbn, 8, 4) . '-' . substr($isbn, 12);

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'author' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'condition' => $this->faker->randomElement(['new', 'used']),
            'genre' => $this->faker->regexify('[A-Za-z]{3,}'),
            'isbn' => $isbnWithDashes,
            'publication_year' => $this->faker->year(),
            'language' => $this->faker->languageCode(),
            'page_count' => $this->faker->numberBetween(1, 1000),
            'publisher' => $this->faker->company(),
            'image_path' => $this->faker->imageUrl(),
            'availability' => $this->faker->boolean(),
        ];
    }
}
