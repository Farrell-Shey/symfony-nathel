<?php


namespace App\DataFixtures;


use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $tag = new Tag();
            $tag->setName('name_' . $i);
            $tag->setType('type_' . $i);
            $tag->addPoolSet($this->getReference('poolSet_' . $i));
            $manager->persist($tag);
            $this->addReference('tag_' . $i, $tag);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PoolSetFixtures::class,
        ];
    }
}
