<?php


namespace App\DataFixtures;


use App\Entity\MappoolMap;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MappoolMapFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $mappoolMap = new MappoolMap();
            $mappoolMap->setMappool($this->getReference('mappool_' . $i));
            $mappoolMap->setBeatmap($this->getReference('beatMap_' . $i));
            $mappoolMap->setUser($this->getReference('user_' . $i));
            $mappoolMap->setMode('mode_' . $i);
            $mappoolMap->addRound($this->getReference('round_' . $i));
            $mappoolMap->addBan($this->getReference('ban_' . $i));
            $mappoolMap->addScore($this->getReference('score_' . $i));
            $manager->persist($mappoolMap);
            $this->addReference('mappoolMam_' . $i, $mappoolMap);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MappoolFixtures::class,
//            BeatMapFixtures::class,
//            UserFixtures::class,
        ];
    }
}
