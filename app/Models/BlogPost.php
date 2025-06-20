<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;        // Додаємо імпорт моделі User
use App\Models\BlogCategory; // Додаємо імпорт моделі BlogCategory

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    const UNKNOWN_USER = 1;
    /**
     * Атрибути, які можна масово призначати.
     *
     * @var array
     */
    protected $fillable
        = [
            'title',
            'slug',
            'category_id',
            'excerpt',
            'content_raw',
            'is_published',
            'published_at',
        ];

    /**
     * Категорія статті.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        // Стаття належить категорії
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Автор статті.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Стаття належить користувачу
        return $this->belongsTo(User::class);
    }
}
