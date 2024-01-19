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
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'author' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'condition' => $this->faker->randomElement(['new', 'used']),
            'genre' => $this->faker->word(),
            'isbn' => $this->faker->isbn13(),
            'publication_year' => $this->faker->year(),
            'language' => $this->faker->languageCode(),
            'page_count' => $this->faker->numberBetween(1, 1000),
            'publisher' => $this->faker->company(),
            'added_date' => $this->faker->date(),
            'image_path' => $this->faker->imageUrl(),
            'availability' => $this->faker->boolean(),
        ];
    }
}
