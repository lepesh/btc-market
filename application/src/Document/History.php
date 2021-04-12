<?php

declare(strict_types=1);

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class History
{
    /**
     * @MongoDB\Id
     */
    protected string $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument=Pair::class)
     */
    protected Pair $pair;

    /**
     * @MongoDB\Field(type="float")
     */
    protected float $price;

    /**
     * @MongoDB\Field(type="date")
     */
    protected DateTime $date;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Pair
     */
    public function getPair(): Pair
    {
        return $this->pair;
    }

    /**
     * @param Pair $pair
     * @return History
     */
    public function setPair(Pair $pair): History
    {
        $this->pair = $pair;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return History
     */
    public function setPrice(float $price): History
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return History
     */
    public function setDate(DateTime $date): History
    {
        $this->date = $date;
        return $this;
    }
}