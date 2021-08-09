<?php


namespace App\DataFixtures;


use App\Entity\Contributor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContributorFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $contributor = new Contributor();
            $contributor->setIsCreator(true);
            $contributor->setUser($this->getReference('user_' . $i));
            $contributor->setPoolSet($this->getReference('poolSet_' . $i));
            $manager->persist($contributor);
            $this->addReference('contributor_' . $i, $contributor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
//            UserFixtures::class,
//            PoolSetFixtures::class,
        ];
    }
}
