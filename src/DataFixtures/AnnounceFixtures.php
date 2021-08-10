<?php


namespace App\DataFixtures;


use App\Entity\Announce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnnounceFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $announce = new Announce();
            $date = new \DateTime();
            $date->setDate(2020,12,1 + $i);
            $announce->setContent('content_' . $i);
            $announce->setDate($date);
            $announce->setTourney($this->getReference('tourney_' . $i));
            $announce->setUser($this->getReference('user_' . $i));
            $manager->persist($announce);
            $this->addReference('announce_' . $i, $announce);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TourneyFixtures::class,
            UserFixtures::class
        ];
    }
}
