<?php

namespace App\Jobs\GenerateCatalog;

class GenerateGoodsFileJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('Generating goods file...');
        parent::handle(); // Важливо викликати parent::handle() або вашу власну логіку
    }
}
