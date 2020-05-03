<?php

namespace App\Service\EntityUpdater;

use App\Entity\Task;
use App\Entity\Tag;

class TaskUpdater implements EntityUpdaterInterface
{
    /**
     * @param Task $existingEntity
     * @param Task $newEntity
     */
    public function update($existingEntity, $newEntity)
    {
        $existingEntity->setTitle($newEntity->getTitle());
        $existingEntity->clearTags();

        /**
         * @var Tag
         */
        foreach ($newEntity->getTags() as $tag) {
            $tag->removeTask($newEntity);
            $existingEntity->addTag($tag);
        }

        $existingEntity->setPriority($newEntity->getPriority());
        $existingEntity->setState($newEntity->getState());
        $existingEntity->setDescription($newEntity->getDescription());
    }
}
