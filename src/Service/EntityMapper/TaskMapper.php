<?php

namespace App\Service\EntityMapper;

use App\Entity\Priority;
use App\Entity\State;
use App\Entity\Tag;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskMapper implements EntityMapperInterface
{
    /**
     * @var \App\Repository\TagRepository
     */
    private $tagRepository;

    /**
     * @var \App\Repository\PriorityRepository
     */
    private $priorityRepository;

    /**
     * @var \App\Repository\StateRepository
     */
    private $stateRepository;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->tagRepository = $manager->getRepository(Tag::class);
        $this->priorityRepository = $manager->getRepository(Priority::class);
        $this->stateRepository = $manager->getRepository(State::class);
    }

    /**
     * @param array $definition
     *
     * @return Task
     */
    public function map(array $definition): Task
    {
        $task = new Task($definition['title']);

        foreach ($definition['tags'] as $tagTitle) {
            $tag = $this->tagRepository->findOneByTitle($tagTitle);

            if (!$tag) {
                $tag = new Tag($tagTitle);
            }

            $task->addTag($tag);
        }

        $priority = $this->priorityRepository->findOneByTitle($definition['priority']);
        $task->setPriority($priority);

        $state = $this->stateRepository->findOneByTitle($definition['state']);
        $task->setState($state);

        $task->setDescription($definition['description']);

        return $task;
    }
}
