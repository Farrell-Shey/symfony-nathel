<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture //implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){

            $manager->persist();
        }
        $manager->flush();
    }

//    public function getDependencies()
//    {
//        return [
//            ProductFixtures::class,
//        ];
//    }
}
