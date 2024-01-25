<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'description',
        'author',
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
    ];
}
