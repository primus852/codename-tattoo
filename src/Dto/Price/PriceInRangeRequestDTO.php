<?php

namespace App\Dto\Price;

use App\Service\ConfigService;
use Symfony\Component\Serializer\Annotation\Groups;

class PriceInRangeRequestDTO
{
    #[Groups(['read', 'write'])]
    public \DateTimeImmutable $datetimeFrom;

    #[Groups(['read', 'write'])]
    public \DateTimeImmutable $datetimeTo;

}
