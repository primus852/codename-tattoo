<?php

namespace App\Dto\TimeTracking;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Client\ClientInfoShortDto;
use App\Dto\User\UserShortDTO;
use App\Entity\TimeTracking;
use App\Enum\TimeTrackingStatus;
use Symfony\Component\Uid\Uuid;


final class TimeTrackingDTO
{
    public Uuid $id;
    public \DateTimeImmutable $dateStart;
    public \DateTimeImmutable $dateEnd;
    public string $description;
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'enum' => ['OPEN', 'NONE', 'NONE_SHOW', 'FINISHED'],
            'example' => 'OPEN'
        ]
    )]
    public TimeTrackingStatus $status;

    public UserShortDTO $user;
    public ClientInfoShortDto $client;
    public array $minutesPerSlot;
    public ?Uuid $overrideToRateHourId;

    public function __construct(TimeTracking $timeTracking, array $slots)
    {
        $userShort = new UserShortDTO($timeTracking->getServiceUser());
        $clientShort = new ClientInfoShortDto($timeTracking->getClient());

        $this->id = $timeTracking->getId();
        $this->dateStart = $timeTracking->getServiceStart();
        $this->dateEnd = $timeTracking->getServiceEnd();
        $this->description = $timeTracking->getServiceDescription();
        $this->status = $timeTracking->getStatus();
        $this->user = $userShort;
        $this->client = $clientShort;
        $this->minutesPerSlot = $slots;
        $this->overrideToRateHourId = $timeTracking->getOverridePrice()?->getId();
    }


}
