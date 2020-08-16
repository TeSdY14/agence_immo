<?php

namespace App\DataFixtures;

use App\Entity\Option;
use App\Entity\Property;
use App\Repository\OptionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class PropertyFixture extends Fixture
{
    /**
     * @var OptionRepository
     */
    private $optionRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->optionRepository = $em->getRepository(Option::class);
    }


    public function load(ObjectManager $manager)
    {
        $optionName = array('Non-renseigné', 'Adapté PMR', 'Garage', 'Cave', 'Gardiennage', 'Parking');
        for ($i = 0; $i <= 5; $i++) {
            $option = new Option();
            $option->setName($optionName[$i]);
            $manager->persist($option);
        }
        $manager->flush();

        $options = $this->optionRepository->findAll();
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $property = new Property();
            $property->setTitle($faker->words(3, true))
                ->setDescription($faker->sentence(3, true))
                ->setSurface($faker->numberBetween(20, 350))
                ->setRooms($faker->numberBetween(1, 10))
                ->setBedrooms($faker->numberBetween(1, 9))
                ->setFloor($faker->numberBetween(0, 15))
                ->setPrice($faker->numberBetween(100000, 1500000))
                ->setHeat($faker->numberBetween(0, count(Property::HEAT)-1))
                ->addOption($options[$faker->numberBetween(0, 2)])
                ->addOption($options[$faker->numberBetween(3, 5)])
                ->setCity($faker->city)
                ->setAddress($faker->address)
                ->setPostalCode($faker->postcode)
                ->setStatus($faker->numberBetween(0, count(Property::STATUS)-1))
                ->setSold(false);
            $manager->persist($property);
        }

        $manager->flush();
    }
}
