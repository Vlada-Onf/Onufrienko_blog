<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id(); // Первинний ключ
            $table->bigInteger('category_id')->unsigned(); // ID категорії, unsigned
            $table->bigInteger('user_id')->unsigned(); // ID автора, unsigned
            $table->string('slug')->unique(); // Унікальний slug для URL
            $table->string('title'); // Заголовок статті
            $table->text('excerpt')->nullable(); // Короткий опис, може бути null
            $table->text('content_raw'); // Сирий текст статті
            $table->text('content_html'); // HTML версія тексту статті (автоматично генерується)
            $table->boolean('is_published')->default(false); // Опубліковано чи ні, за замовчуванням false
            $table->timestamp('published_at')->nullable(); // Дата публікації, може бути null
            $table->timestamps(); // Стовпці created_at та updated_at
            $table->softDeletes(); // Стовпець deleted_at для "м'якого" видалення
            // Зовнішні ключі
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('blog_categories');

            // Індекси для оптимізації запитів
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
