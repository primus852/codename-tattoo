<?php

namespace App\Service;

use App\Dto\TimeTracking\TimeTrackingDTO;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

readonly class PublishService
{

    public function __construct(private HubInterface $hub)
    {
    }

    /**
     * @param TimeTrackingDTO $timeTrackingDTO
     * @return bool
     */
    public function newTimeTrackingAdded(TimeTrackingDTO $timeTrackingDTO): bool
    {
        $update = new Update(
            '/topics/time-tracking/new',
            json_encode($timeTrackingDTO)
        );

        $this->hub->publish($update);

        return true;

    }

}
