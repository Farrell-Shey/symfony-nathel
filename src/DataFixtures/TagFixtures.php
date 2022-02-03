<?php


namespace App\DataFixtures;


use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture // implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $fixtures = [
            [
            'name' => 'std',
            'type' => 'gamemod'],
            [
            'name' => 'mania',
            'type' => 'gamemod'
            ],
            [
            'name' => 'taiko',
            'type' => 'gamemod'
            ],
            [
                'name' => 'ctb',
                'type' => 'gamemod'
            ],
            [
                'name' => 'tournament',
                'type' => 'category'
            ],
            [
                'name' => 'fun',
                'type' => 'category'
            ],
            [
                'name' => 'training',
                'type' => 'category'
            ],
            [
                'name' => 'challenge',
                'type' => 'category'
            ],
            [
                'name' => 'pp_farm',
                'type' => 'category'
            ]


        ];

        foreach($fixtures as $fixture){
            $tag = new Tag();
            $tag->setName($fixture['name']);
            $tag->setType($fixture['type']);
            $manager->persist($tag);
            $this->addReference($fixture['name'], $tag);
        }
        $manager->flush();
    }

}
