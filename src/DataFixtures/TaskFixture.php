<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use App\Entity\State;
use App\Entity\Tag;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class TaskFixture extends Fixture
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

    public function __construct(EntityManagerInterface $manager)
    {
        $this->tagRepository = $manager->getRepository(Tag::class);
        $this->priorityRepository = $manager->getRepository(Priority::class);
        $this->stateRepository = $manager->getRepository(State::class);
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getTaskDefinitions() as $taskDefinition) {
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

    private function getTaskDefinitions(): array
    {
        return [
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
    }
}
