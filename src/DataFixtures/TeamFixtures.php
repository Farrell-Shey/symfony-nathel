<?php


namespace App\DataFixtures;


use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $team = new Team();
            $team->setTeamName('team_' . $i);
            $team->setLogo('logo_' . $i);
            $manager->persist($team);
            $this->setReference('team_' . $i, $team);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TeamUserFixtures::class ,
        ];
    }
}
