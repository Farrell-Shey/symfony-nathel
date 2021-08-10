<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $date = new \DateTime();
            $date->setDate(2021, 12, 1 + $i);
            $user->setOsuId($i);
            $user->setToken('token_' . $i);
            $user->setCountry('country_' . $i);
            $user->setUpdatedAt($date);
            $user->setCreatedAt($date);
            $user->setName('name_' . $i);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TourneyStaffFixtures::class,
        ];
    }
}
