<?php


namespace App\DataFixtures;


use App\Entity\Round;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoundFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $round = new Round();
            $round->setEncounter($i);
            $round->setScore($i);
            $round->setAccuracy($i);
            $round->setMisscount($i);
            $round->setIsV1(true);
            $round->setPlayer($this->getReference('player_' . $i));
            $round->setMappoolMap($this->getReference('mappoolMap_' . $i));
            $manager->persist($round);
            $this->addReference('round_' . $i, $round);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PlayerFixtures::class,
            MappoolMapFixtures::class
        ];
    }
}
