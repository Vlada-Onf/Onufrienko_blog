<?php

namespace App\Jobs\GenerateCatalog;

class GenerateCategoriesJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('Generating categories file...');
        parent::handle();
    }
}
