<?php

namespace App\Repository\Common;

trait TitledEntityRepositoryTrait
{
    /**
     * @param string $title
     *
     * @return object
     */
    public function findOneByTitle(string $title): ?object
    {
        return $this->findOneBy(['title' => $title]);
    }
}
