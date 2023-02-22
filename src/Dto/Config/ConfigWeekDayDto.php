<?php

namespace App\Dto\Config;

use App\Entity\ConfigRateHours;
use App\Entity\ConfigWeekDays;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

final class ConfigWeekDayDto
{
    #[Groups(['read'])]
    public Uuid $id;

    #[Groups(['write', 'read'])]
    public int $dayOfWeek;

    public function __construct(ConfigWeekDays $configWeekDays)
    {
        $this->id = Uuid::fromString($configWeekDays->getId());
        $this->dayOfWeek = $configWeekDays->getDayOfWeek();
    }

}
