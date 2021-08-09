<?php


namespace App\DataFixtures;


use App\Entity\Lobbie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LobbieFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $lobbie = new Lobbie();
            $date = new \DateTime();
            $date->setDate(2020, 12, 1 + $i);
            $lobbie->setIsReplay(true);
            $lobbie->setTourney($this->getReference('tourney_' . $i));
            $lobbie->setStep($this->getReference('step_' . $i));
            $manager->persist($lobbie);
            $this->addReference('lobbie_' . $i, $lobbie);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
//            TourneyFixtures::class,
//            StepFixtures::class,
        ];
    }
}
