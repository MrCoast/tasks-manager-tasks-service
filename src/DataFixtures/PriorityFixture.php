<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Priority;

class PriorityFixture extends Fixture
{
    private const PRIORITY_TITLES = ['low', 'moderate', 'high'];

    public function load(ObjectManager $manager)
    {
        foreach (self::PRIORITY_TITLES as $priorityTitle) {
            $priority = new Priority($priorityTitle);
            $manager->persist($priority);
        }

        $manager->flush();
    }
}
