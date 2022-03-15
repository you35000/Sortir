<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outing;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\User;
use App\Repository\CityRepository;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private ObjectManager $manager;

    public function __construct(UserPasswordHasherInterface $passwordHasher)

    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;


        $this->addCitys();
        $this->addPlace();
        $this->addCampus();
        $this->addUser();
        $this->addState();
        $this->addOuting();
    }

    public function addCitys()
    {
        /**
         * @var City $faker
         */
        $faker = Factory::create('fr_FR');


        for ($i = 0; $i < 5; $i++) {

            $city = new City();
            $city->setName($this->faker->city)
                ->setPostCode($this->faker->postcode);

            $this->manager->persist($city);
        }
        $this->manager->flush();
    }


    public function addPlace()
    {

        $faker = Factory::create('fr_FR');

        $cities = $this->manager->getRepository(City::class)->findAll();
        $places = ['Cinéma', 'Patinoire', 'Bowling', 'LaserGame', 'Bar', 'Tricot', 'EscapeGame', 'Scrabble', 'Luge', 'Poney', 'Piscine', 'Jeux vidéos', 'Football'];


        for ($i = 0; $i < 10; $i++) {
            $place = new Place();
            $place->setName($faker->randomElement($places))
                ->setStreet($faker->streetAddress)
                ->setCity($faker->randomElement($cities))
                ->setLongitude($this->faker->longitude)
                ->setLatitude($this->faker->latitude);

            $this->manager->persist($place);
        }
        $this->manager->flush();
    }

    public function addCampus()
    {

        $campus1 = new Campus();
        $campus1->setName('Chartes-de-bretagne');
        $this->manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setName('Nantes');
        $this->manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setName('Niort');
        $this->manager->persist($campus3);
        $this->manager->flush();
    }

    public function addState()
    {

        $state = new State();
        $state->setLibelle('Créée');
        $this->manager->persist($state);

        $state = new State();
        $state->setLibelle('Ouverte');
        $this->manager->persist($state);

        $state = new State();
        $state->setLibelle('Clôturée');
        $this->manager->persist($state);


        $state = new State();
        $state->setLibelle('Activité en cours');
        $this->manager->persist($state);

        $state = new State();
        $state->setLibelle('Passée');
        $this->manager->persist($state);

        $state = new State();
        $state->setLibelle('Annulée');
        $this->manager->persist($state);

        $state = new State();
        $state->setLibelle('Historisée');
        $this->manager->persist($state);

        $this->manager->flush();
    }


    public function addUser()
    {
        $campus = $this->manager->getRepository(Campus::class)->findAll();
        $user = new User();
        $user->setFirstName('Toto')
            ->setLastName('Dupont')
            ->setPseudo('Toto D.')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setEmail('toto.dupont@gmail.com')
            ->setPhone($this->faker->mobileNumber)
            ->setIsActive('1')
            ->setCampus($this->faker->randomElement($campus))
            ->setPassword($this->hasher->hashPassword($user, '123456'));

        $this->manager->persist($user);

        for ($i = 0; $i < 20; $i++) {
            $fname = $this->faker->firstName;
            $lname = $this->faker->lastName;
            $pseudo = $fname . ' ' . substr($lname, 0, 1) . '.';
//            $email = strtolower($fname).'.'.strtolower($lname).'@free.fr';
            $user = new User();
            $user->setFirstName($fname)
                ->setLastName($lname)
                ->setPseudo($pseudo)
                ->setRoles(['ROLE_USER'])
                ->setEmail($this->faker->freeEmail)
                ->setPhone($this->faker->mobileNumber)
                ->setIsActive('1')
                ->setCampus($this->faker->randomElement($campus))
                ->setPassword($this->hasher->hashPassword($user, '123456'));

            $this->manager->persist($user);
        }

        $this->manager->flush();
    }

    public function addOuting()
    {
        $faker = Factory::create('fr_FR');
        $states = $this->manager->getRepository(State::class)->findAll();
        $users = $this->manager->getRepository(User::class)->findAll();
        $places = $this->manager->getRepository(Place::class)->findAll();


        for ($i = 0; $i < 50; $i++) {
            $place = $this->faker->randomElement($places);
            $startDate = $faker->dateTimeThisYear();
            $outing = new Outing();
            $outing->setName($place->getName())
                ->setStartDate(date_add($startDate, date_interval_create_from_date_string('9 months')))
                ->setDuration(($faker->numberBetween(6, 30)) * 10);

            $date = clone $outing->getStartDate();
            $date->modify($faker->numberBetween(-7, -2) . " days");
            $outing->setLimitDate($date)
                ->setNbInscription($faker->numberBetween(2, 20))
                ->setOrganizer($faker->randomElement($this->manager->getRepository(User::class)->findAll()))
                ->setPlace($place);
            $outing->setCampus($outing->getOrganizer()->getCampus());

            //Ajout de participant
            $nbMax = $this->faker->numberBetween(0, $outing->getNbInscription());

            for ($j = 0; $j < $nbMax; $j++) {
                $newAttendee = $this->faker->randomElement($users);
                if (!$outing->getAttendees()->contains($newAttendee)) {
                    $outing->addAttendee($newAttendee);
                }
            }

            //Gestion de l'état selon la date :
            $now = new DateTime('now');
            $dateEnd = date_add($outing->getStartDate(), date_interval_create_from_date_string($outing->getDuration() . ' minutes'));

            if ($outing->getAttendees()->count() == 0 && $outing->getStartDate() > $now) {
                $outing->setState($states[0]);
            } //Activité en cours
            elseif ($outing->getStartDate() < $now && $now < $dateEnd) {
                $outing->setState($states[3]);
            } //Activité passée
            elseif ($dateEnd < $now) {
                $outing->setState($states[4]);
            } //Activité historisée
            elseif (date_add($outing->getStartDate(), date_interval_create_from_date_string('1 month')) < $now) {
                $outing->setState($states[6]);
            } //Activité Cloturée
            elseif ($outing->getLimitDate() < $now || $outing->getNbInscription() == $outing->getAttendees()->count()) {
                $outing->setState($states[2]);
            } //Autre random
            else {
                $othersStates[] = $states[1];
                $othersStates[] = $states[5];
                $outing->setState($faker->randomElement($othersStates));
            }


            $this->manager->persist($outing);
        }

        $this->manager->flush();
    }
}
