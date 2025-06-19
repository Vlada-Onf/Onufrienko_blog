<?php

namespace App\Jobs\GenerateCatalog;

class ArchiveUploadsJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('Archiving uploads...');
        parent::handle();
    }
}
