<?php

namespace App\DataFixtures;

use App\DataFixtures\Common\EntityLoaderInterface;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StateFixture extends Fixture
{
    private const STATE_TITLES = ['To Do', 'In Analysis', 'In Progress', 'Done'];

    /**
     * @var EntityLoaderInterface
     */
    private $loader;

    /**
     * @param EntityLoaderInterface $loader
     */
    public function __construct(EntityLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loader->load($manager, self::STATE_TITLES, State::class);
    }
}
