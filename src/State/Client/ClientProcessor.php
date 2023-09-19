<?php

namespace App\State\Client;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Client;
use App\Exception\ClientAlreadyExistsException;
use Doctrine\ORM\EntityManagerInterface;

class ClientProcessor implements ProcessorInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ClientAlreadyExistsException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Post) {
            $repo = $this->entityManager->getRepository(Client::class);
            $oldClient = $repo->findOneByNameOrNameShort($data->name, $data->nameShort);

            if ($oldClient === null) {
                $client = new Client();
                $client->setName($data->name);
                $client->setNameShort($data->nameShort);
                $client->setClientNumber($repo->getNextClientNumber());
                $repo->save($client, true);
                return $client;
            }

            throw new ClientAlreadyExistsException('CLIENT_EXISTS');
        }

        return $data;
    }
}
