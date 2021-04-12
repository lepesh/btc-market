<?php

declare(strict_types=1);

namespace App\Service\CurrencyMarket;

use DateTime;

interface CurrencyMarket
{
    /**
     * @param string $symbol
     * @param DateTime|null $startDate
     * @return array|HistoryDTO[]
     */
    public function history(string $symbol, DateTime $startDate = null): array;

    /**
     * @param string $symbol
     * @return bool
     */
    public function symbolSupported(string $symbol): bool;
}
