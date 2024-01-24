<?php

namespace App\DTOs\Book;

use Illuminate\Http\Request;

class CreateBookDTO
{
    public function __construct(
        public string $title,
        public string $author,
        public string $description,
        public float $price,
        public string $condition,
        public string $genre,
        public string $isbn,
        public int $publication_year,
        public string $language,
        public int $page_count,
        public string $publisher,
        public string $added_date,
        public string $image_path,
        public bool $availability
    ) {
    }

    public static function makeFromRequest(Request $request): self
    {
        return new self(
            $request->get('title'),
            $request->get('author'),
            $request->get('description'),
            $request->get('price'),
            $request->get('condition'),
            $request->get('genre'),
            $request->get('isbn'),
            $request->get('publication_year'),
            $request->get('language'),
            $request->get('page_count'),
            $request->get('publisher'),
            $request->get('added_date'),
            $request->get('image_path'),
            $request->get('availability'),
        );
    }
}
