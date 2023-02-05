<?php

namespace App\Dto\Config;

use App\Entity\ConfigRateHours;
use App\Service\ConfigService;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

final class ConfigRateHoursSlotsDto
{

    #[Groups(['read', 'write'])]
    public \DateTimeImmutable $datetimeFrom;

    #[Groups(['read', 'write'])]
    public \DateTimeImmutable $datetimeTo;

    #[Groups(['read', 'write'])]
    public array $minutesPerSlot;

    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to, array $configuredRateHours)
    {
        $this->datetimeFrom = $from;
        $this->datetimeTo = $to;

        $minutes = ConfigService::getRateHoursBetweenDates($from, $to, $configuredRateHours);
        $this->minutesPerSlot = $minutes;

    }

}
