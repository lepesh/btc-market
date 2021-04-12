<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Document\Pair;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class PairFixtures extends Fixture
{
    private const PAIRS = ['BTCUSD', 'BTCEUR', 'BTCGBP'];

    public function load(ObjectManager $manager)
    {
        foreach (self::PAIRS as $name) {
            $pair = new Pair();
            $pair->setSymbol($name);
            $manager->persist($pair);
        }
        $manager->flush();
    }
}
