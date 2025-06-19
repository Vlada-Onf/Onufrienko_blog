<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    public $chunk;
    public $fileNum;

    public function __construct($chunk, int $fileNum)
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу
        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }

    public function handle()
    {
        $this->debug("Generating prices file chunk {$this->fileNum} for products: " . implode(', ', $this->chunk->toArray()));
        parent::handle();
    }
}
