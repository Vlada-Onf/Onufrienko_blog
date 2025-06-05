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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id(); // Первинний ключ
            $table->bigInteger('parent_id')->unsigned()->default('1'); // ID батьківської категорії, unsigned для невід'ємних чисел, default '1'
            $table->string('slug')->unique(); // Унікальний slug для URL, String 255
            $table->string('title'); // Заголовок категорії, String 255
            $table->text('description')->nullable(); // Опис, може бути null, Text
            $table->timestamps(); // Стовпці created_at та updated_at
            $table->softDeletes(); // Стовпець deleted_at для "м'якого" видалення
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
