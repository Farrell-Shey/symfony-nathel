<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture // implements DependentFixtureInterface
{
      private $passwordHasher;

      public function __construct(UserPasswordHasherInterface $passwordHasher)
      {
          $this->passwordHasher = $passwordHasher;
      }

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
            $user->setPassword($this->passwordHasher->hashPassword(
                             $user,
                             'password'
                         ));
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

