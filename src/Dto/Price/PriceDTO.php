<?php

namespace App\Dto\Price;

use App\Entity\Price;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

class PriceDTO
{
    #[Groups(['read'])]
    public Uuid $id;

    #[Groups(['write', 'read'])]
    public string $timeFrom;

    #[Groups(['write', 'read'])]
    public string $timeTo;

    #[Groups(['write', 'read'])]
    public float $priceNet;

    #[Groups(['write', 'read'])]
    public string $category;

    #[Groups(['write', 'read'])]
    public string $name;

    #[Groups(['write', 'read'])]
    public int $weekDay;

    public function __construct(Price $configPrice)
    {
        /**
         * Add One Second to output to easier understand it
         */
        $oneSecond = new \DateInterval('PT1S');

        $this->id = Uuid::fromString($configPrice->getId());
        $this->timeFrom = $configPrice->getTimeFrom()->format('H:i');
        $this->timeTo = $configPrice->getTimeTo()->add($oneSecond)->format('H:i');
        $this->priceNet = $configPrice->getPriceNet();
        $this->category = $configPrice->getCategory();
        $this->name = $configPrice->getName();
        $this->weekDay = $configPrice->getWeekDay();
    }
}
