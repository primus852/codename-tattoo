<?php

namespace App\Dto\TimeTracking;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Dto\Client\ClientInfoShortDto;
use App\Dto\Price\PriceDTO;
use App\Dto\User\UserShortDTO;
use App\Entity\Price;
use App\Entity\TimeTracking;
use App\Service\ConfigService;


final class TimeTrackingListDTO
{

    public array $timeTrackings = array();
    public array $configuredPrices;

    public function __construct(Paginator|iterable $timeTrackingsPaginator, array $configuredPrices)
    {
        foreach ($timeTrackingsPaginator as $timeTracking) {
            $slots = ConfigService::getRateHoursBetweenDates($timeTracking->getServiceStart(), $timeTracking->getServiceEnd(), $configuredPrices);
            $this->timeTrackings[] = new TimeTrackingDTO($timeTracking, $slots);
        }

        $this->configuredPrices = $this->_convertConfiguredPrices($configuredPrices);
    }

    /**
     * @param array $configuredPrices
     * @return Price[]
     */
    private function _convertConfiguredPrices(array $configuredPrices): array
    {
        $cfg = array();

        foreach ($configuredPrices as $configuredPrice) {
            $cfg[] = new PriceDTO($configuredPrice);
        }

        return $cfg;
    }
}
