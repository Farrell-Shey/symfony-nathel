<?php


namespace App\DataFixtures;


use App\Entity\Beatmap;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BeatMapFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $beatMap = new Beatmap();
            $beatMap->setDifficulty('diff_' . $i);
            $beatMap->setBpm($i);
            $beatMap->setAr($i);
            $beatMap->setCs($i);
            $beatMap->setDrain($i);
            $beatMap->setAccuracy($i);
            $beatMap->setHitLength($i);
            $beatMap->setModeInt($i);
            $beatMap->setUrl('url_' . $i);
            $beatMap->addMappoolMap($this->getReference('mapPoolMap_' . $i));
            $beatMap->setBeatmapset($this->getReference('beatMap_' . $i));
            $manager->persist($beatMap);
            $this->addReference('beatMap_' . $i, $beatMap);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BeatmapsetFixtures::class,
        ];
    }
}
