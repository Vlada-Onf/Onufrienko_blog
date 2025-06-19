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

        // Спочатку кешуємо продукти (виконується синхронно, без черги)
        // Використання dispatchNow() означає негайне виконання, не додаючи в чергу.
        GenerateCatalogCacheJob::dispatchSync(); // Змінено на dispatchSync() для негайного виконання

        // Створюємо ланцюг завдань формування файлів з цінами
        $chainPrices = $this->getChainPrices();

        // Основні підзавдання
        $chainMain = [
            new GenerateCategoriesJob, // Генерація категорій
            new GenerateDeliveriesJob, // Генерація способів доставок
            new GeneratePointsJob,     // Генерація пунктів видачі
        ];

        // Підзавдання, які мають виконуватися останніми
        $chainLast = [
            // Архівування файлів і перенесення архіву в публічний каталог
            new ArchiveUploadsJob,
            // Відправка повідомлення зовнішньому сервісу про те, що можна завантажувати новий файл каталога товарів
            new SendPriceRequestJob,
        ];

        // Об'єднуємо всі ланцюги
        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        // Запускаємо перший Job з ланцюгом наступних
        GenerateGoodsFileJob::withChain($chain)->dispatch(); // Correct usage for chaining

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
        // Імітуємо продукти, які потрібно розбити на фрагменти
        $products = collect([101, 102, 103, 104, 105, 106, 107, 108, 109, 110]); // Більше продуктів для кращої демонстрації
        $fileNum = 1;

        // Розбиваємо продукти на фрагменти по 2 елементи
        foreach ($products->chunk(2) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }

        return $result;
    }
}
