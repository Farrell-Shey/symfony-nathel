<?php


namespace App\DataFixtures;


use App\Entity\Beatmapset;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BeatmapsetFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $BeatMapset = new Beatmapset();
            $BeatMapset->setName('name_' . $i);
            $BeatMapset->setCreator('creator_' . $i);
            $BeatMapset->setArtist('artist_' . $i);
            $manager->persist($BeatMapset);
            $this->addReference('beatMapset_' . $i, $BeatMapset);
        }
        $manager->flush();
    }
}
