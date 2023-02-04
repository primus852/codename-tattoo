<?php

namespace App\Dto\Client;

use App\Entity\Client;

class ClientInfoShortDto
{
    public string $id;
    public string $name;
    public string $nameShort;

    public function __construct(Client $client)
    {
        $this->id = $client->getId();
        $this->name = $client->getName();
        $this->nameShort = $client->getNameShort();
    }

}
