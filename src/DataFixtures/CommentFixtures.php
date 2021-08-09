<?php


namespace App\DataFixtures;


use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $date = new \DateTime();
            $date->setDate(2021, 12, 1 + $i);
            $comment->setContent('content_'. $i);
            $comment->setAddlike($i);
            $comment->setAnnounce($this->getReference('announce_' . $i));
            $comment->setUser($this->getReference('user_' . $i));
            $manager->persist($comment);
            $this->addReference('comment_' . $i, $comment);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
//            AnnounceFixtures::class,
//            UserFixtures::class,
        ];
    }
}
