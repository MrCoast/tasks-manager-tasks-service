<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Task;
use App\Repository\PriorityRepository;
use App\Repository\StateRepository;
use App\Repository\TagRepository;

class TaskFixture extends Fixture
{
    private const TASK_DEFINITIONS = [
        [
            'title' => 'breakfast',
            'tags' => ['home'],
            'priority' => 'high',
            'state' => 'To Do',
            'description' => 'very tasty breaskfast',
        ],
        [
            'title' => 'read news',
            'tags' => ['home', 'finance'],
            'priority' => 'moderate',
            'state' => 'To Do',
            'description' => 'read Telegram channels, watch YouTube',
        ],
        [
            'title' => 'chat with friends',
            'tags' => ['home'],
            'priority' => 'low',
            'state' => 'To Do',
            'description' => 'use Telegram',
        ],
        [
            'title' => 'create daily agenda',
            'tags' => ['jobs'],
            'priority' => 'high',
            'state' => 'In Analysis',
            'description' => 'use iPhone Notes app',
        ],
    ];

    private $tagRepository;

    private $priorityRepository;

    private $stateRepository;

    public function __construct(TagRepository $tagRepository, PriorityRepository $priorityRepository, StateRepository $stateRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->priorityRepository = $priorityRepository;
        $this->stateRepository = $stateRepository;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::TASK_DEFINITIONS as $taskDefinition) {
            $task = $this->getEntityFromDefinition($taskDefinition);
            $manager->persist($task);
        }

        $manager->flush();
    }

    private function getEntityFromDefinition(array $definition): Task
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
