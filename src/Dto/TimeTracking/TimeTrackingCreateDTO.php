<?php

namespace App\Dto\TimeTracking;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class TimeTrackingCreateDTO
{
    #[Assert\NotBlank]
    public \DateTimeImmutable $dateStart;

    public \DateTimeImmutable $dateEnd;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    public Uuid $clientId;

    public string $userId;
}
