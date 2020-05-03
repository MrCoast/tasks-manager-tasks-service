<?php

namespace App\DataFixtures;

use App\DataFixtures\Common\EntityLoaderInterface;
use App\Entity\Priority;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PriorityFixture extends Fixture
{
    private const PRIORITY_TITLES = ['low', 'moderate', 'high'];

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
        $this->loader->load($manager, self::PRIORITY_TITLES, Priority::class);
    }
}
