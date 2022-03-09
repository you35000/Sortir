<?php

namespace App\Repository;

use App\Entity\Outing;
use App\Entity\User;
use App\Form\Model\SearchOuting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    /**
     * @param Outing $entity
     * @param bool|bool $flush
     */
    public function add(Outing $entity, bool $flush = true): void
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
    public function remove(Outing $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function filters(SearchOuting $search, User $user){

        $qb = $this->createQueryBuilder('o');

        if ($search->getIsOrganizer()){
            $qb->andWhere('o.organizer = :user')
            ->setParameter('user',$user);
        }

        if ($search->getIsRegistered()){
            $qb->innerJoin('outing_user','ou', Join::INNER_JOIN, 'ou.outing_id = outing_id')
                ->add('where', 'ou.user_id = :id')
                ->setParameter('id', $user->getId());
        }

        if ($search->getIsOver()){
            $qb->andWhere('o.startDate < CURRENT_DATE()');
        }



        $query = $qb->getQuery();
        return $query->execute();
    }

    // /**
    //  * @return Outing[] Returns an array of Outing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Outing
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
