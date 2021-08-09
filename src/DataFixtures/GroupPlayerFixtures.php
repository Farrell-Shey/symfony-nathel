<?php


namespace App\DataFixtures;


use App\Entity\GroupPlayer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupPlayerFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $groupPlayer = new GroupPlayer();
            $groupPlayer->setRanking($i);
            $groupPlayer->setPlayer($this->getReference('player_' . $i));
            $groupPlayer->setPool($this->getReference('pool_' . $i));
            $manager->persist($groupPlayer);
            $this->addReference('groupPlayer_' . $i, $groupPlayer);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
//            PlayerFixtures::class,
//            GroupFixtures::class,
        ];
    }
}
