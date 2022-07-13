<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Arena;
use Doctrine\ORM\ORMException;
use App\Entity\TrainingSession;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method TrainingSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingSession[]    findAll()
 * @method TrainingSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingSession::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TrainingSession $entity, bool $flush = true): void
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
    public function remove(TrainingSession $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TrainingSession[] Returns an array of TrainingSession objects
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
    public function findOneBySomeField($value): ?TrainingSession
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getReservedForDateAndArena(Arena $arena, string $date)
    {
        $start=(new \DateTime($date))->setTime(0,0,0);
        $end=(new \DateTime($date))->setTime(23,59,9999);
        return $this->createQueryBuilder('ts')
            ->andWhere('ts.arena = :arena')
            ->andWhere('ts.sessionDate between :start and :end')
            ->setParameter('arena',$arena)
            ->setParameter('start',$start)
            ->setParameter('end',$end)
            ->getQuery()
            ->getResult()
            ;
    }


    public function getUserSessions(User $user,string $type='all')
    {
        $qb=$this->createQueryBuilder('ts')
            ->join('ts.arena','a')
            ->addSelect('a')
        ;
        if($user->getUserType()==User::PLAYER_TYPE)
        {
            $qb->andWhere('ts.player = :user');
        }elseif($user->getUserType()==User::MANAGER_TYPE)
        {
            $qb->andWhere('ts.buyer = :user');
        }

        if($type!='all')
        {
            $type=array_search($type,TrainingSession::TRAINING_STATUSES);
            $qb->andWhere('ts.status = :type')
                ->setParameter('type',$type);
        }

        return $qb
        ->setParameter('user',$user)
        ->addOrderBy('ts.sessionDate','ASC')
        ->getQuery()
        ->getResult()
        ;
    }

    public function getUserReservedTrainings(User $user,Arena $arena,\DateTime $start,\DateTime $end)
    {
        $qb=$this->createQueryBuilder('ts')
            ->andWhere('ts.arena = :arena')
            ->andWhere('ts.sessionDate between :start and :end')
            ->setParameter('arena',$arena)
            ->setParameter('start',$start)
            ->setParameter('end',$end)
        ;
        if($user->getUserType()==User::PLAYER_TYPE)
        {
            $qb->andWhere('ts.player = :user');
        }elseif($user->getUserType()==User::MANAGER_TYPE)
        {
            $qb->andWhere('ts.buyer = :user');
        }

        return $qb
        ->setParameter('user',$user)
        ->addOrderBy('ts.sessionDate','ASC')
        ->getQuery()
        ->getResult()
        ;
    }
}
