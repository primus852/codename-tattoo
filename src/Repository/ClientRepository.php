<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function save(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByNameOrNameShort(string $name, string $nameShort): ?Client
    {
        return $this->createQueryBuilder('c')
            ->where('c.nameShort = :nameShort')
            ->orWhere('c.name = :name')
            ->setParameter('name', $name)
            ->setParameter('nameShort', $nameShort)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getNextClientNumber(): string
    {
        $maxNumber = $this->createQueryBuilder('c')
            ->select('MAX(cast_as_integer(c.clientNumber))')
            ->getQuery()
            ->getSingleScalarResult();

        return $maxNumber !== null ? (string)($maxNumber + 1) : '1';
    }

}
