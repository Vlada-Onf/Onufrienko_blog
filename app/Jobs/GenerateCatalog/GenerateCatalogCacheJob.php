<?php
namespace App\Jobs\GenerateCatalog;

class GenerateCatalogCacheJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('Generating catalog cache...');
        // Тут була б логіка кешування продуктів
        parent::handle();
    }
}
