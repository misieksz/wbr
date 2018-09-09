<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\Entity\Category;


class CategoryFixtures extends Fixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $categoryList = array(
                'goalball' => 'Goalball',
                'projekty' => 'Projekty',
                'inne' => 'Inne'
            );


        foreach ($categoryList as $key => $name) {
            $Category = new Category();

            $Category->setName($name)
                    ->setSlug($name);

            $manager->persist($Category);
            $this->addReference('category_'.$key, $Category);
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
