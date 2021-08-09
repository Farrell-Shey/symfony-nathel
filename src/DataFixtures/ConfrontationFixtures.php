<?php


namespace App\DataFixtures;


use App\Entity\Confrontation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfrontationFixtures extends Fixture //implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $confrontation = new Confrontation();
            $date = new \DateTime();
            $date->setDate(2021, 12, 1 + $i);
            $confrontation->setFinalDate($date);
            $confrontation->setFirstDate($date);
            $confrontation->setStatus('status_' . $i);
            $confrontation->setIsFirstPicker(true);
            $confrontation->setStep($this->getReference('step_' . $i));
            $confrontation->setTourney($this->getReference('tourney_' . $i));
            $confrontation->setRef($this->getReference('tourneyStaff_' . $i));
            $confrontation->addPool($this->getReference('group_' . $i));
            $confrontation->addBan($this->getReference('ban_' . $i));
            $manager->persist($confrontation);
            $this->addReference('confrontation_' . $i, $confrontation);
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
