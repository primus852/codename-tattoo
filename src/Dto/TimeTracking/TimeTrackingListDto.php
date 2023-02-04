<?php

namespace App\Dto\TimeTracking;

use App\Dto\Client\ClientInfoShortDto;
use App\Dto\User\UserInfoShortDto;
use App\Entity\TimeTracking;


final class TimeTrackingListDto
{

    public array $timeTrackings = array();

    public function __construct($timeTrackingsPaginator)
    {
        foreach ($timeTrackingsPaginator as $timeTracking) {
            $this->timeTrackings[] = new TimeTrackingDto($timeTracking);
        }
    }
}
