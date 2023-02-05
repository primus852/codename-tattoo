<?php

namespace App\Dto\Config;

use App\Entity\ConfigRateHours;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use function Sodium\add;

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
    }

}
