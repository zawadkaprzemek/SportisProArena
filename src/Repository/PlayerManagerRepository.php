<?php

namespace App\Repository;

use App\Entity\PlayerManager;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlayerManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerManager[]    findAll()
 * @method PlayerManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerManager::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PlayerManager $entity, bool $flush = true): void
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
    public function remove(PlayerManager $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getManagerPlayers(User $manager)
    {
        return $this->createQueryBuilder('pm')
            ->andWhere('pm.manager = :manager')
            ->andWhere('pm.accepted = true')
            ->andWhere('pm.active = true')
            ->setParameter('manager',$manager)
            ->getQuery()
            ->getResult()
            ;
    }


    public function isPlayerAssignedToManager(User $manager,User $player)
    {
        return $this->createQueryBuilder('pm')
            ->andWhere('pm.manager = :manager')
            ->andWhere('pm.player = :player')
            ->setParameter('manager',$manager)
            ->setParameter('player',$player)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return PlayerManager[] Returns an array of PlayerManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayerManager
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
