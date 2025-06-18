<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BlogPostAfterDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private int $blogPostId; // Змінено на тип

    /**
     * Create a new job instance.
     *
     * @param int $blogPostId
     * @return void
     */
    public function __construct(int $blogPostId) // Змінено тип
    {
        $this->blogPostId = $blogPostId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        logs()->warning("Видалено запис в блозі [{$this->blogPostId}]");
    }
}
