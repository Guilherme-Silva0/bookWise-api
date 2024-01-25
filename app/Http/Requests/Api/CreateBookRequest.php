<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /*
            [
                "title" => "Mr."
                "description" => "Et commodi non ut. Consequatur et facere error delectus perferendis possimus tempora. Assumenda exercitationem error reprehenderit itaque ullam omnis ipsum. Harum non quam vel ab aut quos."
                "author" => "Dr. Ezra Lebsack III"
                "price" => 35.05
                "condition" => "used"
                "genre" => "maiores"
                "isbn" => "9783769870817"
                "publication_year" => "2000"
                "language" => "co"
                "page_count" => 441
                "publisher" => "Smitham, Adams and Mayer"
                "image_path" => "https://via.placeholder.com/640x480.png/00ee99?text=officiis"
                "availability" => true
            ]
        */

        return [
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:10', 'max:1000'],
            'author' => ['required', 'min:3', 'max:255'],
            'price' => ['required', 'numeric'],
            'condition' => ['sometimes', 'in:new,used'],
            'genre' => ['required', 'min:3', 'max:255'],
            'isbn' => ['sometimes', 'min:3', 'max:255', 'regex:/^\d{3}-\d{1}-\d{4}-\d{4}-\d{1}$/', 'unique:books'],
            'publication_year' => ['required', 'numeric', 'max:' . date('Y')],
            'language' => ['required', 'max:255'],
            'page_count' => ['required', 'numeric'],
            'publisher' => ['sometimes', 'min:3', 'max:255'],
            'image_path' => ['required', 'min:3', 'max:255'],
            'availability' => ['sometimes', 'boolean'],
        ];
    }
}
