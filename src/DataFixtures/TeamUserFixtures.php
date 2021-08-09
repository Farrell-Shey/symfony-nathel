<?php


namespace App\DataFixtures;


use App\Entity\Team;
use App\Entity\TeamUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TeamUserFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $teamUser = new TeamUser();
            $teamUser->setPlayer($this->getReference('player_' . $i));
            $teamUser->setTeam($this->getReference('team_' . $i));
            $teamUser->setIsCapitain(true);
            $manager->persist($teamUser);
            $this->addReference('teamUser_' . $i, $teamUser);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PlayerFixtures::class,
        ];
    }
}
