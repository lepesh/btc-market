<?php

declare(strict_types=1);

namespace App\Service;

use App\Document\Pair;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;

class PairService
{
    private DocumentManager $dm;
    private ObjectRepository $pairRepository;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $this->pairRepository = $dm->getRepository(Pair::class);
    }

    public function getBySymbol(string $symbol): ?Pair
    {
        return $this->pairRepository->findOneBy(['symbol' => $symbol]);
    }
}
