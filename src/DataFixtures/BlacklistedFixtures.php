<?php


namespace App\DataFixtures;


use App\Entity\Blacklisted;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BlacklistedFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $blacklisted = new Blacklisted();
            $blacklisted->setReason('reason_' . $i);
            $blacklisted->setSeverity('severity_' . $i);
            $blacklisted->setUser($this->getReference('user_' . $i));
            $manager->persist($blacklisted);
            $this->addReference('blacklisted_' . $i, $blacklisted);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
