<?php

namespace App\DataFixtures;

use App\DataFixtures\Common\EntityLoaderInterface;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends Fixture
{
    private const TAG_TITLES = ['training', 'jobs', 'home', 'business', 'finance'];

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
        $this->loader->load($manager, self::TAG_TITLES, Tag::class);
    }
}
