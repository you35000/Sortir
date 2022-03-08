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


        for ($i = 0; $i < 10; $i++) {

            $city = new City();
            $city->setName($faker->city)
                ->setPostCode($faker->postcode);

            $this->manager->persist($city);
        }
        $this->manager->flush();
    }



    public function addPlace()
    {

        /**
         * @var Place $faker
         */
        $faker = Factory::create('fr_FR');

        $cities = $this->manager->getRepository(City::class)->findAll();


        for ($i = 0; $i < 10; $i++) {
            $place = new Place();
            $place->setName($faker->company)
                ->setStreet($faker->streetAddress)
                ->setCity($faker->randomElement($cities));

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


    public function __construct(UserPasswordHasherInterface $passwordHasher)

    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $passwordHasher;
    }
    public function addUser()
    {

        $campus = $this->manager->getRepository(Campus::class)->findAll();
        for ($i = 0; $i < 10; $i++) {

            $user = new User();
            $user->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setEmail($this->faker->email)
                ->setPhone($this->faker->mobileNumber)
                ->setIsActive('1')
                ->setCampus($this->faker->randomElement($campus))

                ->setPassword($this->hasher->hashPassword($user, '123
                 '));

            $this->manager->persist($user);
        }

        $this->manager->flush();
    }

    public function addOuting()
    {
        $faker = Factory::create('fr_FR');
        $campus = $this->manager->getRepository(Campus::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $startDate = new DateTime();
            $startDate = $faker->dateTimeThisYear();
            $outing = new Outing();
            $outing->setName('Outing' . $i)
                ->setStartDate(date_add($startDate, date_interval_create_from_date_string('1 year')))
                ->setDuration($faker->numberBetween(60, 300));

            $date = clone $outing->getStartDate();
            $date->modify("-1 day");
            $outing->setLimitDate($date)
                ->setNbInscription($faker->numberBetween(2, 20))
                ->setCampus($faker->randomElement($campus))
                ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
                ->setOrganizer($faker->randomElement($this->manager->getRepository(User::class)->findAll()))
                ->setPlace($faker->randomElement($this->manager->getRepository(Place::class)->findAll()));
            $this->manager->persist($outing);
        }

        $this->manager->flush();

        //TODO : revoir interval

        // $outings = $this->manager->getRepository(Outing::class)->findAll();
        // foreach ($outing as $key => $out) {
        //     $out->setLimitDate(date_sub($startDate, DateInterval::createFromDateString('1 day')));
        //     $this->manager->persist($out);
        // }
        // $this->manager->flush();
    }
}
