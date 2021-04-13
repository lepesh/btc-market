<?php

declare(strict_types=1);

namespace App\Service;

use App\Document\History;
use App\Document\Pair;
use App\Repository\HistoryRepository;
use App\Service\CurrencyMarket\CurrencyMarket;
use DateInterval;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Psr\Log\LoggerInterface;

class HistoryService
{
    private DocumentManager $dm;
    private HistoryRepository $historyRepository;
    private CurrencyMarket $currencyMarket;
    private LoggerInterface $logger;

    public function __construct(
        DocumentManager $dm,
        CurrencyMarket $currencyMarket,
        LoggerInterface $logger
    ) {
        $this->dm = $dm;
        $this->historyRepository = $dm->getRepository(History::class);
        $this->currencyMarket = $currencyMarket;
        $this->logger = $logger;
    }

    /**
     * @param Pair $pair
     * @param DateTime|null $dateStart
     * @param DateTime|null $dateEnd
     * @return array|History[]
     */
    public function filterHistory(Pair $pair, DateTime $dateStart = null, DateTime $dateEnd = null): array
    {
        return $this->historyRepository->filter($pair, $dateStart, $dateEnd);
    }

    public function updateMarketHistory(Pair $pair, DateTime $startDate = null): bool
    {
        $startDate = $this->resolveStartDate($startDate, $pair);
        try {
            $ticks = $this->currencyMarket->history($pair->getSymbol(), $startDate);
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return false;
        }

        foreach ($ticks as $dto) {
            $history = History::createFromHistoryDTO($dto)->setPair($pair);
            $this->dm->persist($history);
        }
        $this->dm->flush();

        return true;
    }

    /**
     * To avoid duplication, we need to use the date following the last date in the history table.
     *
     * @param DateTime|null $startDate
     * @param Pair $pair
     * @return DateTime|null
     */
    private function resolveStartDate(?DateTime $startDate, Pair $pair): ?DateTime
    {
        $latestDate = $this->historyRepository->getLatestDate($pair);
        if (!$latestDate) {
            // if history table is empty then last date will be 10 days before today (API restriction)
            $latestDate = (new DateTime())->sub(new DateInterval('P10D'));
        }
        $latestDate->add(new DateInterval('PT1H'));
        $startDate = $startDate ?? $latestDate;
        if ($startDate < $latestDate) {
            $startDate = $latestDate;
        }

        return $startDate;
    }
}
