<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePointsJob extends AbstractJob
{
    public function handle()
    {
        //$f = 1 / 0;
        $this->debug('Generating points file...');
        parent::handle();
    }
}
