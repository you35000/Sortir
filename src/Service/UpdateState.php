<?php

namespace App\Service;

use App\Entity\Outing;
use App\Repository\OutingRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class UpdateState
{
    private static ?\DateTimeInterface $lastUpdate = null;
    private OutingRepository $outingRepository;
    private StateRepository $stateRepository;
    private EntityManagerInterface $em;


    public function __construct(OutingRepository $outingRepository, StateRepository $stateRepository, EntityManagerInterface $em)
    {
        $this->outingRepository = $outingRepository;
        $this->stateRepository = $stateRepository;
        $this->em = $em;
    }

//    public function testLastUpdate()
//    {
//        if (static::$lastUpdate !== null && self::$lastUpdate < date_sub(new \DateTime(), date_interval_create_from_date_string('10 seconds'))) {
//            $this->updateOutings();
//
//        } elseif (static::$lastUpdate === null) {
//            static::setLastUpdate(new \DateTime('now'));
//            $this->updateOutings();
//        }
//    }

    public function updateOutings()
    {
        $outings = $this->outingRepository->findAllExceptHistorized();
        $states = $this->stateRepository->findAll();
        foreach ($outings as $o) {
            $now = new \DateTime();
            $now2 = clone $now;
            $limitDateHistorize = $now2->modify('-1 month');

            if ($o->getStartDate() < $limitDateHistorize) { //On marque l'activité en Historisée
                $o->setState($states[6]);
                $this->em->persist($o);
            } elseif ($o->getState()->getLibelle() != 'Passée'
                && $o->getStartDate() > $limitDateHistorize
                && date_add(clone $o->getStartDate(), date_interval_create_from_date_string($o->getDuration() . ' minutes')) < $now) { // On marque l'activité en Passée
                $o->setState($states[4]);
                $this->em->persist($o);
            } elseif ($o->getState()->getLibelle() != 'Clôturée'
                && $o->getLimitDate() < $now
                && $o->getStartDate() > $now) {

                $o->setState($states[2]);
                
                $this->em->persist($o);
            } elseif ($o->getState()->getLibelle() != 'Activité en cours'
                && $o->getState()->getLibelle() != 'Annulée'
                && $o->getStartDate() < $now
                && date_add(clone $o->getStartDate(), date_interval_create_from_date_string($o->getDuration() . ' minutes')) > $now) { // On marque l'activité en En cours
                $o->setState($states[3]);
                $this->em->persist($o);
            }
        }
        $this->em->flush();
    }

    /**
     * @param \DateTimeInterface $lastUpdate
     */
    public static function setLastUpdate(\DateTimeInterface $lastUpdate): void
    {
        self::$lastUpdate = $lastUpdate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public static function getLastUpdate(): ?\DateTimeInterface
    {
        return self::$lastUpdate;
    }


}