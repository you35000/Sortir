<?php

namespace App\Repository;

use App\Entity\Outing;
use App\Entity\User;
use App\Entity\State;
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

    public function findAllNotHistorized(){
        return $this->createQueryBuilder('o')->where('o.state <> :state')
            ->setParameter('state',$this->getEntityManager()->getRepository(State::class)->find(7))
            ->getQuery()->execute();
    }

    public function filters(SearchOuting $search, User $user)
    {

        $qb = $this->createQueryBuilder('o');

        if ($search->getCampus()) {
            $qb->andWhere('o.campus = :campus')
                ->setParameter('campus', $search->getCampus());
        }

        if ($search->getSearch()) {
            $qb->andWhere('o.name Like :search')
                ->setParameter('search', '%' . $search->getSearch() . '%');
        }

        if (!is_null($search->getDateStarted())) {
                $qb->andWhere('o.startDate > :datestart')
                ->setParameter('datestart', $search->getDateStarted());
        }

        if (!is_null($search->getDateEnded())) {
            $qb->andWhere('o.startDate < :dateend')
                ->setParameter('dateend', $search->getDateEnded());
        }

        if ($search->getIsOrganizer()) {
            $qb->andWhere('o.organizer = :user')
                ->setParameter('user', $user);
        }

//        if ($search->getIsRegistered()){
//            $qb->join('outing_user','ou')
//                ->add('where', 'ou.user_id = :id')
//                ->setParameter('id', $user->getId());
//        }

        if ($search->getIsOver()) {
            $qb->andWhere('o.startDate < CURRENT_DATE()');
        }


        $query = $qb->getQuery();
        return $query->execute();
    }
}
