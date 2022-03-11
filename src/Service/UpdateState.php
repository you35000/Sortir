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

    public function testLastUpdate()
    {
        if (static::$lastUpdate !== null && self::$lastUpdate < date_sub(new \DateTime(), date_interval_create_from_date_string('10 seconds'))) {
            $this->updateOutings();

        } elseif (static::$lastUpdate === null) {
            static::setLastUpdate(new \DateTime('now'));
            $this->updateOutings();
        }
    }

    public function updateOutings()
    {
        $outings = $this->outingRepository->findAll();
        $states = $this->stateRepository->findAll();
        foreach ($outings as $o) {
            $limitDateHistorize = date_sub(new \DateTime('now'), date_interval_create_from_date_string('1 month'));
            if ($o->getState()->getLibelle() != 'Historisée' && $o->getStartDate() < $limitDateHistorize) {
                $o->setState($states[6]);
                $this->em->persist($o);
            } elseif ($o->getState()->getLibelle() != 'Créée' && $o->getStartDate() > $limitDateHistorize && $o->getStartDate() < new \DateTime()) {
                $o->setState($states[4]);
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