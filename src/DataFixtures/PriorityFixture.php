<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PriorityFixture extends Fixture
{
    private const PRIORITY_TITLES = ['low', 'moderate', 'high'];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::PRIORITY_TITLES as $priorityTitle) {
            $priority = new Priority($priorityTitle);
            $manager->persist($priority);
        }

        $manager->flush();
    }
}
