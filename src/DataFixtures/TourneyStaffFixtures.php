<?php


namespace App\DataFixtures;


use App\Entity\TourneyStaff;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TourneyStaffFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $tourneyStaff = new TourneyStaff();
            $tourneyStaff->setUser($this->getReference('user_' . $i));
            $tourneyStaff->setTourney($this->getReference('tourney_' . $i));
            $tourneyStaff->addConfrontation($this->getReference('confrontation_' . $i));
            $tourneyStaff->setRole('role_'. $i);
            $manager->persist($tourneyStaff);
            $this->addReference('tourneyStaff_' . $i, $tourneyStaff);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
//            ConfrontationFixtures::class,
//            TourneyFixtures::class,
        ];
    }
}
