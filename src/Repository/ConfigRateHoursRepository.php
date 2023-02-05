<?php

namespace App\Repository;

use App\Entity\ConfigRateHours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigRateHours>
 *
 * @method ConfigRateHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigRateHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigRateHours[]    findAll()
 * @method ConfigRateHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigRateHoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigRateHours::class);
    }

    public function save(ConfigRateHours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConfigRateHours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
