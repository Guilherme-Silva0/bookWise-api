<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'price' => 'R$ ' . number_format($this->price, 2, ',', '.'),
            'condition' => $this->condition,
            'genre' => $this->genre,
            'publication_year' => $this->publication_year,
            'language' => $this->language,
            'page_count' => $this->page_count,
            'publisher' => $this->publisher,
            'added_date' => Carbon::parse($this->added_date)->format('Y-m-d H:i:s'),
            'image_path' => $this->image_path,
            'availability' => $this->availability ? __('Available') : __('Unavailable'),
            'isbn' => $this->isbn,
            // 'created_at' => $this->created_at
            // 'updated_at' => $this->updated_at
        ];
    }
}
