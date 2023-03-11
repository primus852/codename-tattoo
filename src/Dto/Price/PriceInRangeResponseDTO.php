<?php

namespace App\Dto\Price;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class PriceInRangeResponseDTO
{
    #[Groups(['read', 'write'])]
    public DateTimeInterface $datetimeFrom;

    #[Groups(['read', 'write'])]
    public DateTimeInterface $datetimeTo;

    #[Groups(['read'])]
    public array $minutesPerSlot;

    /**
     * @param DateTimeImmutable $datetimeFrom
     * @param DateTimeImmutable $datetimeTo
     * @param array $minutes
     */
    public function __construct(DateTimeImmutable $datetimeFrom, DateTimeImmutable $datetimeTo, array $minutes)
    {
        $this->datetimeFrom = $datetimeFrom;
        $this->datetimeTo = $datetimeTo;
        $this->minutesPerSlot = $minutes;
    }

}
