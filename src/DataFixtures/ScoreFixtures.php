<?php


namespace App\DataFixtures;


use App\Entity\Score;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScoreFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $score = new Score();
            $date = new \DateTime();
            $date->setDate(2020, 12, 1 + $i);
            $score->setScore($i);
            $score->setNote('note_' . $i);
            $score->setAcc('acc_' . $i);
            $score->setCombo($i);
            $score->setPerfect($i);
            $score->setGood($i);
            $score->setBad($i);
            $score->setMiss($i);
            $score->setUpdatedAt($date);
            $score->setUser($this->getReference('user' . $i));
            $score->setMappoolMap($this->getReference('mappoolMap' . $i));
            $manager->persist($score);
            $this->addReference('score_' . $i, $score);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
//            MappoolMapFixtures::class,
            UserFixtures::class,
        ];
    }
}
