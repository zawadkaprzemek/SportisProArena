<?php

namespace App\Repository;

use App\Entity\TrainingUnitThrowConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrainingUnitThrowConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingUnitThrowConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingUnitThrowConfig[]    findAll()
 * @method TrainingUnitThrowConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingUnitThrowConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingUnitThrowConfig::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TrainingUnitThrowConfig $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TrainingUnitThrowConfig $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TrainingUnitThrowConfig[] Returns an array of TrainingUnitThrowConfig objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrainingUnitThrowConfig
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
