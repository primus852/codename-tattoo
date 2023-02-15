<?php

namespace App\Dto\TimeTracking;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Dto\Client\ClientInfoShortDto;
use App\Dto\User\UserInfoShortDto;
use App\Entity\TimeTracking;
use App\Service\ConfigService;


final class TimeTrackingListDto
{

    public array $timeTrackings = array();

    public function __construct(Paginator|iterable $timeTrackingsPaginator, array $configuredRateHours)
    {
        foreach ($timeTrackingsPaginator as $timeTracking) {
            $slots = ConfigService::getRateHoursBetweenDates($timeTracking->getServiceStart(), $timeTracking->getServiceEnd(), $configuredRateHours);
            $this->timeTrackings[] = new TimeTrackingDto($timeTracking, $slots, $configuredRateHours);
        }
    }
}
