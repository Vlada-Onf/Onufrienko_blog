<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // Додано для логування

class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('generate-catalog'); // Черга за замовчуванням для всіх завдань каталогу
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->debug('done'); // За замовчуванням логуємо "done"
    }

    /**
     * Допоміжний метод для логування.
     *
     * @param string $msg
     * @return void
     */
    protected function debug(string $msg)
    {
        $class = static::class; // Отримуємо назву поточного класу
        $msg = $msg . " [{$class}]";
        Log::info($msg); // Використовуємо фасад Log
    }
}
