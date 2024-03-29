<?php

namespace App\Dto\Client;

use App\Entity\Client;

class ClientDTO
{
    public string $id;
    public string $name;
    public string $nameShort;
    public string $clientNumber;

    public function __construct(Client $client)
    {
        $this->id = $client->getId();
        $this->name = $client->getName();
        $this->nameShort = $client->getNameShort();
        $this->clientNumber = $client->getClientNumber();
    }

}
