<?php

namespace App\Dto\Config;

use App\Entity\ConfigRateHours;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

final class ConfigRateHoursDto
{
    #[Groups(['read'])]
    public Uuid $id;

    #[Groups(['write', 'read'])]
    public string $hourFrom;

    #[Groups(['write', 'read'])]
    public string $hourTo;

    #[Groups(['write', 'read'])]
    public float $priceNet;

    #[Groups(['write', 'read'])]
    public string $category;

    #[Groups(['write', 'read'])]
    public array $weekDays = array();

    public function __construct(ConfigRateHours $configRateHours)
    {
        /**
         * Add One Second to output to easier understand it
         */
        $oneSecond = new \DateInterval('PT1S');

        $this->id = Uuid::fromString($configRateHours->getId());
        $this->hourFrom = $configRateHours->getHourFrom()->format('H:i');
        $this->hourTo = $configRateHours->getHourTo()->add($oneSecond)->format('H:i');
        $this->priceNet = $configRateHours->getPriceNet();
        $this->category = $configRateHours->getCategory();

        foreach ($configRateHours->getConfigWeekDays() as $configWeekDay){
            $this->weekDays[] = $configWeekDay->getDayOfWeek();
        }
    }

}
