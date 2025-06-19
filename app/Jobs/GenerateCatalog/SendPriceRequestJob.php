<?php

namespace App\Jobs\GenerateCatalog;

class SendPriceRequestJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('Sending price request...');
        parent::handle();
    }
}
