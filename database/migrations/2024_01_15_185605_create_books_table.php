<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->enum('condition', ['new', 'used'])->default('new');
            $table->string('genre')->nullable();
            $table->string('isbn')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('language')->nullable();
            $table->integer('page_count')->nullable();
            $table->string('publisher')->nullable();
            $table->date('added_date')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('availability')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
