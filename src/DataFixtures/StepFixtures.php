<?php


namespace App\DataFixtures;


use App\Entity\Step;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StepFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $step = new Step();
            $date = new \DateTime();
            $date->setDate(2021, 12, 1 + $i);
            $step->setStep('step_' . $i);
            $step->setDate($date);
            $step->setPosition($i);
            $step->setTourney($this->getReference('tourney_' . $i));
            $step->setMappool($this->getReference('mappool_' . $i));
            $step->addConfrontation($this->getReference('confrontation_' . $i));
            $step->addLobby($this->getReference('lobbie_' . $i));
            $manager->persist($step);
            $this->addReference('step_' . $i, $step);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TourneyFixtures::class,
//            MappoolFixtures::class,
        ];
    }
}
