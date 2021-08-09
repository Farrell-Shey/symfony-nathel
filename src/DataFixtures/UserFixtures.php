<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture  implements DependentFixtureInterface
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
            $user->addTourneyStaff($this->getReference('tourneyStaff_' . $i));
            $user->addPlayer($this->getReference('player_' . $i));
            $user->addMappoolMap($this->getReference('mappoolMap_' . $i));
            $user->addContributor($this->getReference('contributor_' . $i));
            $user->addScore($this->getReference('score_' . $i));
            $user->addInvitation($this->getReference('invitation_' . $i));
            $user->addAnnounce($this->getReference('announce_' . $i));
            $user->addComment($this->getReference('comment_' . $i));
            $user->addBlacklisted($this->getReference('blackListed_' . $i));
            $user->addMappoolFollowed($this->getReference('mappoolFollowed_' . $i));
            $user->addMappoolMap($this->getReference('mappoolMap_' . $i));
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
