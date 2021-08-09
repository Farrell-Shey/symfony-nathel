<?php


namespace App\DataFixtures;


use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $player = new Player();
            $player->setState('state_' . $i);
            $player->setUser($this->getReference('user_' . $i));
            $player->setTourney($this->getReference('tourney_' . $i));
            $player->addGroupPlayer($this->getReference('groupPlayer_' . $i));
            $player->addTeamUser($this->getReference('teamUser_' . $i));
            $player->addRound($this->getReference('round_' . $i));
            $manager->persist($player);
            $this->addReference('player_' . $i, $player);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
//            TourneyFixtures::class,
        ];
    }
}
