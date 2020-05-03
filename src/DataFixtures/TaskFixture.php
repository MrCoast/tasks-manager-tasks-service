<?php

namespace App\DataFixtures;

use App\Service\EntityMapper\TaskMapper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixture extends Fixture
{
    /**
     * @var TaskMapper
     */
    private $mapper;

    /**
     * @param TaskMapper $mapper
     */
    public function __construct(TaskMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getTaskDefinitions() as $taskDefinition) {
            $task = $this->mapper->map($taskDefinition);
            $manager->persist($task);
        }

        $manager->flush();
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
