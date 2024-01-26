<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
