<?php

declare(strict_types=1);

namespace App\Repository;

use App\Document\History;
use App\Document\Pair;
use DateTime;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class HistoryRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function getLatestDate(Pair $pair): ?DateTime
    {
        /** @var History $history */
        $history = $this->createQueryBuilder()
            ->select('date')
            ->field('pair')->equals($pair)
            ->sort('date', 'DESC')
            ->limit(1)
            ->getQuery()
            ->getSingleResult();

        return $history ? clone $history->getDate() : null;
    }
}
