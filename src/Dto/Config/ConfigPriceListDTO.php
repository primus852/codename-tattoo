<?php

namespace App\Dto\Config;


class ConfigPriceListDTO
{
    public array $prices = array();

    public function __construct($configsPaginator)
    {
        foreach ($configsPaginator as $price) {
            $this->prices[] = new ConfigPriceDTO($price);
        }
    }
}
