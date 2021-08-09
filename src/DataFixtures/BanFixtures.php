<?php


namespace App\DataFixtures;


use App\Entity\Ban;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BanFixtures extends Fixture //implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $ban = new Ban();
            $ban->setConfrontation($this->getReference('confrontation_' . $i));
            $ban->setIsBlueSide(true);
            $manager->persist($ban);
            $this->addReference('ban_' . $i, $ban);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
//            ConfrontationFixtures::class,
        ];
    }
}
