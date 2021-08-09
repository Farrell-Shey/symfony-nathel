<?php


namespace App\DataFixtures;


use App\Entity\MappoolFollowed;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MappoolFollowedFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $mappoolFollowed = new MappoolFollowed();
            $mappoolFollowed->setUser($this->getReference('user_' . $i));
            $mappoolFollowed->setMappool($this->getReference('mappool_' . $i));
            $manager->persist($mappoolFollowed);
            $this->addReference('mappoolFollowed_' . $i, $mappoolFollowed);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
//            MappoolFixtures::class,
        ];
    }
}
