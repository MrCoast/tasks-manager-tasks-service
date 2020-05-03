<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends Fixture
{
    private const TAG_TITLES = ['training', 'jobs', 'home', 'business', 'finance'];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::TAG_TITLES as $tagTitle) {
            $tag = new Tag($tagTitle);
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
