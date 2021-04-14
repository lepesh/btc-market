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

    /**
     * @param Pair $pair
     * @param DateTime|null $dateStart
     * @param DateTime|null $dateEnd
     * @return array|History[]
     */
    public function filter(Pair $pair, DateTime $dateStart = null, DateTime $dateEnd = null): array
    {
        $qb = $this->createQueryBuilder()
            ->field('pair')->equals($pair)
            ->sort('date', 'ASC');
        if ($dateStart) {
            $qb->field('date')->gte($dateStart);
        }
        if ($dateEnd) {
            $qb->field('date')->lte($dateEnd);
        }

        return $qb->getQuery()->execute()->toArray();
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
