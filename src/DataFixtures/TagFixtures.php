<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\Entity\Tag;


class TagFixtures extends Fixture implements OrderedFixtureInterface{

    public function load(ObjectManager $manager) {

        $tagList = array(
                'goalball',
                'sport',
                'niepełnosprawni'
            );


        foreach ($tagList as $name) {
            $Tag = new Tag();

            $Tag->setName($name)
                    ->setSlug($name);

            $manager->persist($Tag);
            $this->addReference('tag_'.$name, $Tag);
        }

        $manager->flush();
    }

    /**
     * 
     * @return int
     */
    public function getOrder() {
        return 0;
    }

}
