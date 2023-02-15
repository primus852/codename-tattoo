<?php

namespace App\Dto\TimeTracking;

use ApiPlatform\Metadata\ApiProperty;
use App\Dto\Client\ClientInfoShortDto;
use App\Dto\Config\ConfigRateHoursDto;
use App\Dto\User\UserInfoShortDto;
use App\Entity\TimeTracking;
use App\Enum\TimeTrackingStatus;
use Symfony\Component\Uid\Uuid;


final class TimeTrackingDto
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

    public UserInfoShortDto $user;
    public ClientInfoShortDto $client;
    public array $minutesPerSlot;
    public ?Uuid $overrideToRateHourId;
    public array $configuredRateHours;

    public function __construct(TimeTracking $timeTracking, array $slots, array $configuredRateHours)
    {
        $userShort = new UserInfoShortDto($timeTracking->getServiceUser());
        $clientShort = new ClientInfoShortDto($timeTracking->getClient());

        $this->id = $timeTracking->getId();
        $this->dateStart = $timeTracking->getServiceStart();
        $this->dateEnd = $timeTracking->getServiceEnd();
        $this->description = $timeTracking->getServiceDescription();
        $this->status = $timeTracking->getStatus();
        $this->user = $userShort;
        $this->client = $clientShort;
        $this->minutesPerSlot = $slots;
        $this->overrideToRateHourId = $timeTracking->getOverrideRateHour()?->getId();
        $this->configuredRateHours = $this->_convertConfiguredRateHours($configuredRateHours);
    }

    /**
     * @param array $configuredRateHours
     * @return array
     */
    private function _convertConfiguredRateHours(array $configuredRateHours): array
    {
        $cfg = array();

        foreach ($configuredRateHours as $configuredRateHour) {
            $cfg[] = new ConfigRateHoursDto($configuredRateHour);
        }

        return $cfg;
    }
}
