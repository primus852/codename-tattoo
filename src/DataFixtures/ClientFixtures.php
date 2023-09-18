<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\ClientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture implements FixtureGroupInterface
{

    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public static function getGroups(): array
    {
        return ['clients'];
    }

    public function load(ObjectManager $manager): void
    {
        /**
         * Create the Internal
         */
        $this->_generateClient($manager);

        /**
         * Create 25 Random Clients
         */
        $this->_generateClient($manager, false, 250);
    }

    /**
     * @param ObjectManager $manager
     * @param bool $isInternal
     * @param int $numberOfClients
     * @return void
     */
    private function _generateClient(ObjectManager $manager, bool $isInternal = true, int $numberOfClients = 1): void
    {
        if ($isInternal) {
            $client = new Client();
            $client->setName('Intern');
            $client->setNameShort('INT');
            $client->setClientNumber(1);
            $manager->persist($client);
            $manager->flush();
        } else {
            $faker = Factory::create();
            for ($i = 0; $i < $numberOfClients; $i++) {
                $company = $faker->company();
                $companyClean = preg_replace("/[^a-zA-Z]/", "", $company);
                $companyShort = strtoupper(substr($companyClean, 0, 6));

                if ($this->_clientExists($companyShort)) {
                    $i--;
                    continue;
                }

                $client = new Client();
                $client->setName($company);
                $client->setNameShort($companyShort);
                $client->setClientNumber(($i + 2));
                $manager->persist($client);
                $manager->flush();
            }

        }
    }

    /**
     * @param string $nameShort
     * @return bool
     */
    private function _clientExists(string $nameShort): bool
    {
        $client = $this->clientRepository->findOneBy(array(
            'nameShort' => $nameShort
        ));

        if (!$client) {
            return false;
        }

        return true;
    }
}
