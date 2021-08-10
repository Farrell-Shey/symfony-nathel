<?php


namespace App\DataFixtures;


use App\Entity\Tourney;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

class TourneyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $tourney = new Tourney();
            $date = new \DateTime();
            $date->setDate(2021, 12, 1 + $i);
            $tourney->setName('name_' . $i);
            $tourney->setAcronym('acrro_' . $i);
            $tourney->setIteration($i);
            $tourney->setFollow($i);
            $tourney->setNbplayer($i);
            $tourney->setNbInscrits($i);
            $tourney->setMode('mode_' . $i);
            $tourney->setis_scorev2(true);
            $tourney->setis_team(true);
            $tourney->setis_qualif(true);
            $tourney->setGroupstages(true);
            $tourney->setBracketFormat('bracket_' . $i);
            $tourney->setRegStartDate($date);
            $tourney->setRegCloseDate($date);
            $tourney->setColorTheme('color' . $i);
            $tourney->setUpdatedAt($date);
            $tourney->setCreatedAt($date);
            $tourney->setPoolSet($this->getReference('poolSet_' . $i));
            $this->addReference('tourney_' . $i, $tourney);
            $manager->persist($tourney);
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
