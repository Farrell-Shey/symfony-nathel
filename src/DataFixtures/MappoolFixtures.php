<?php


namespace App\DataFixtures;


use App\Entity\Mappool;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MappoolFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $mappool = new Mappool();
            $date = new \DateTime();
            $date->setDate(2021, 12 , 1 + $i);
            $mappool->setName('name_' . $i);
            $mappool->setThumbnail('thumbnail_' . $i);
            $mappool->setFollow($i);
            $mappool->setUpdatedAt($date);
            $mappool->setCreatedAt($date);
            $mappool->setPoolSet($this->getReference('poolSet_' . $i));
            $mappool->setContributor($this->getReference('contributor_' . $i));
            $manager->persist($mappool);
            $this->addReference('mappool_' . $i, $mappool);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ContributorFixtures::class,
        ];
    }
}
