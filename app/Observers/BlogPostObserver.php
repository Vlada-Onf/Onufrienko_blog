<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogPostObserver
{
    /**
     * Обробка події "creating" (перед створенням) запису.
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost): void
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        $this->setHtml($blogPost);
        $this->setUser($blogPost);
    }

    /**
     * Обробка події "updating" (перед оновленням) запису.
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function updating(BlogPost $blogPost): void
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        $this->setHtml($blogPost);
    }
    /**
     * Якщо поле published_at порожнє і is_published є true,
     * то генеруємо поточну дату.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setPublishedAt(BlogPost $blogPost): void
    {
        if (empty($blogPost->published_at) && $blogPost->is_published) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * Якщо псевдонім (slug) порожній,
     * то генеруємо псевдонім зі заголовка.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setSlug(BlogPost $blogPost): void
    {
        if (empty($blogPost->slug)) {
            $blogPost->slug = Str::slug($blogPost->title);
        }
    }

    /**
     * Встановлюємо значення полю content_html з поля content_raw.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setHtml(BlogPost $blogPost): void
    {
        if ($blogPost->isDirty('content_raw')) {
            $blogPost->content_html = $blogPost->content_raw;
        }
    }

    /**
     * Якщо user_id не вказано, то встановимо юзера 1.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setUser(BlogPost $blogPost): void
    {
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;

    }
}
