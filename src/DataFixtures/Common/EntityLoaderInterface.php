<?php

namespace App\DataFixtures\Common;

use Doctrine\Common\Persistence\ObjectManager;

interface EntityLoaderInterface
{
    public function load(ObjectManager $manager, array $titles, string $entityClass);
}
