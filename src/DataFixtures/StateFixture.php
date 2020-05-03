<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StateFixture extends Fixture
{
    private const STATE_TITLES = ['To Do', 'In Analysis', 'In Progress', 'Done'];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::STATE_TITLES as $stateTitle) {
            $state = new State($stateTitle);
            $manager->persist($state);
        }

        $manager->flush();
    }
}
