<?php

namespace App\DataFixtures\Common;

use Doctrine\Common\Persistence\ObjectManager;

class TitledEntityLoader implements EntityLoaderInterface
{
    /**
     * @param ObjectManager $manager
     * @param array $titles
     * @param string $entityClass
     */
    public function load(ObjectManager $manager, array $titles, string $entityClass)
    {
        foreach ($titles as $title) {
            $entity = new $entityClass($title);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
