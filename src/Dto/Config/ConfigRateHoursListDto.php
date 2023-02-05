<?php

namespace App\Dto\Config;

use App\Dto\Client\ClientInfoShortDto;
use App\Dto\TimeTracking\TimeTrackingDto;
use App\Dto\User\UserInfoShortDto;
use App\Entity\TimeTracking;


final class ConfigRateHoursListDto
{

    public array $rateHours = array();

    public function __construct($configsPaginator)
    {
        foreach ($configsPaginator as $config) {
            $this->rateHours[] = new ConfigRateHoursDto($config);
        }
    }
}
