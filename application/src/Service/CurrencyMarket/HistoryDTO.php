<?php

declare(strict_types=1);

namespace App\Service\CurrencyMarket;

use DateTime;

class HistoryDTO
{
    private float $price;
    private DateTime $date;

    public function __construct(float $price, DateTime $date)
    {
        $this->price = $price;
        $this->date = $date;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }
}
