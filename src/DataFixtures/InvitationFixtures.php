<?php


namespace App\DataFixtures;


use App\Entity\Invitation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvitationFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $invitation = new Invitation();
            $date = new \DateTime();
            $date->setDate(2020, 12, 1 + $i);
            $invitation->setCreatedAt($date);
            $invitation->setDeletedAt($date);
            $invitation->setUser($this->getReference('user_' . $i));
            $invitation->setPoolset($this->getReference('poolSet_' . $i));
            $manager->persist($invitation);
            $this->addReference('invitation_' . $i, $invitation);
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
