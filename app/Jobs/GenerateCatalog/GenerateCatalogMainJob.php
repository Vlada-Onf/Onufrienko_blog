<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Chain; // Додано для використання Chain

class GenerateCatalogMainJob extends AbstractJob
{
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function handle()
    {
        $this->debug('start');
        
        GenerateCatalogCacheJob::dispatchSync();
        $chainPrices = $this->getChainPrices();

        $chainMain = [
            new GenerateCategoriesJob,
            new GenerateDeliveriesJob,
            new GeneratePointsJob,
        ];

        $chainLast = [
            new ArchiveUploadsJob,

            new SendPriceRequestJob,
        ];
        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        GenerateGoodsFileJob::withChain($chain)->dispatch();

        $this->debug('finish');
    }

    /**
     * Формування ланцюгів підзавдань по генерації файлів з цінами.
     *
     * @return array
     */
    private function getChainPrices(): array
    {
        $result = [];
        $products = collect([101, 102, 103, 104, 105, 106, 107, 108, 109, 110]);
        $fileNum = 1;

        foreach ($products->chunk(2) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }

        return $result;
    }
}
