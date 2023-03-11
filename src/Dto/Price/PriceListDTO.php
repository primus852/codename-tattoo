<?php

namespace App\Dto\Price;

use ApiPlatform\Doctrine\Orm\Paginator;
use Symfony\Component\Serializer\Annotation\Groups;

class PriceListDTO
{
    #[Groups(['read'])]
    public array $prices = array();

    /**
     * @param Paginator $prices
     */
    public function __construct(Paginator $prices)
    {
        foreach ($prices as $price) {
            $this->prices[] = new PriceDTO($price);
        }
    }
}
