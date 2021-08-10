<?php


namespace App\DataFixtures;


use App\Entity\PoolSet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PoolSetFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $poolSet = new PoolSet();
            $date = new \DateTime();
            $date->setDate(2021, 12, 1 + $i);
            $poolSet->setCreatedAt($date);
            $poolSet->setUpdatedAt($date);
            $poolSet->setName('name_' . $i);
            $manager->persist($poolSet);
            $this->addReference('poolSet_' . $i, $poolSet);
        }
        $manager->flush();
    }
}
