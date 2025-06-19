<?php

namespace App\Jobs\GenerateCatalog;

class GenerateDeliveriesJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('Generating deliveries file...');
        parent::handle();
    }
}
