<?php

namespace App\Repository;

use App\Entity\ConfigWeekDays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigWeekDays>
 *
 * @method ConfigWeekDays|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigWeekDays|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigWeekDays[]    findAll()
 * @method ConfigWeekDays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigWeekDaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigWeekDays::class);
    }

    public function save(ConfigWeekDays $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConfigWeekDays $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ConfigWeekDays[] Returns an array of ConfigWeekDays objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ConfigWeekDays
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
