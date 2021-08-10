<?php


namespace App\DataFixtures;


use App\Entity\Widget;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WidgetFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $widget = new Widget();
            $widget->setContent('content_' . $i);
            $widget->setTitle('title_' . $i);
            $widget->setPosition($i);
            $widget->setPage('page_' . $i);
            $widget->setTourney($this->getReference('tourney_' . $i));
            $manager->persist($widget);
            $this->addReference('widget_' . $i, $widget);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TourneyFixtures::class,
        ];
    }
}
