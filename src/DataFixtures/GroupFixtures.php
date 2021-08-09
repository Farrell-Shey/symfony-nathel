<?php


namespace App\DataFixtures;


use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $group = new Group();
            $group->setTourney($this->getReference('tourney' . $i));
            $group->addConfrontation($this->getReference('confrontation_' . $i));
            $group->addGroupPlayer($this->getReference('groupPlayerç_' . $i));
            $manager->persist($group);
            $this->addReference('gourp_' . $i, $group);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
//            TourneyFixtures::class
        ];
    }
}
