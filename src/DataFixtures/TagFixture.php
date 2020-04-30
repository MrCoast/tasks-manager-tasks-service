<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tag;

class TagFixture extends Fixture
{
    private const TAG_TITLES = ['training', 'jobs', 'home', 'business', 'finance'];

    public function load(ObjectManager $manager)
    {
        foreach (self::TAG_TITLES as $tagTitle) {
            $tag = new Tag($tagTitle);
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
