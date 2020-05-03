<?php

namespace App\Tests\Service;

use App\Entity\Priority;
use App\Entity\State;
use App\Entity\Tag;
use App\Repository\PriorityRepository;
use App\Repository\StateRepository;
use App\Repository\TagRepository;
use App\Service\EntityMapper\TaskMapper;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskMapperTest extends TestCase
{
    /**
     * @var TaskMapper
     */
    private $taskMapper;

    public function setUp()
    {
        $manager = $this->getEntityManagerMock();

        $this->taskMapper = new TaskMapper($manager);
    }

    /**
     * @dataProvider getTaskDefinitions
     */
    public function testMap($definition)
    {
        $task = $this->taskMapper->map($definition);

        $this->assertEquals($definition['title'], $task->getTitle());
        $this->assertEquals(count($definition['tags']), count($task->getTags()->toArray()));
        $this->assertEquals($definition['priority'], $task->getPriority()->getTitle());
        $this->assertEquals($definition['state'], $task->getState()->getTitle());
        $this->assertEquals($definition['description'], $task->getDescription());
    }

    public function getTaskDefinitions(): array
    {
        return [
            [[
                'title' => 'breakfast',
                'tags' => ['home'],
                'priority' => 'high',
                'state' => 'To Do',
                'description' => 'very tasty breaskfast',
            ]],
            [[
                'title' => 'read news',
                'tags' => ['home', 'finance'],
                'priority' => 'moderate',
                'state' => 'To Do',
                'description' => 'read Telegram channels, watch YouTube',
            ]],
            [[
                'title' => 'chat with friends',
                'tags' => ['home'],
                'priority' => 'low',
                'state' => 'To Do',
                'description' => 'use Telegram',
            ]],
            [[
                'title' => 'create daily agenda',
                'tags' => ['jobs'],
                'priority' => 'high',
                'state' => 'In Analysis',
                'description' => 'use iPhone Notes app',
            ]],
        ];
    }

    private function getEntityManagerMock(): MockObject
    {
        $tagRepository = $this->createMock(TagRepository::class);
        $tagRepository
            ->expects($this->any())
            ->method('findOneByTitle')
            ->willReturnCallback($this->getRepositoryReturnCallback(Tag::class));

        $priorityRepository = $this->createMock(PriorityRepository::class);
        $priorityRepository
            ->expects($this->any())
            ->method('findOneByTitle')
            ->willReturnCallback($this->getRepositoryReturnCallback(Priority::class));

        $stateRepository = $this->createMock(StateRepository::class);
        $stateRepository
            ->expects($this->any())
            ->method('findOneByTitle')
            ->willReturnCallback($this->getRepositoryReturnCallback(State::class));

        $manager = $this->createMock(EntityManager::class);
        $manager
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnMap([
                [Tag::class, $tagRepository],
                [Priority::class, $priorityRepository],
                [State::class, $stateRepository],
            ]);

        return $manager;
    }

    private function getRepositoryReturnCallback(string $entityClass): callable
    {
        return function (string $title) use ($entityClass) {
            if ($title === 'non-existent') {
                return null;
            }

            return new $entityClass($title);
        };
    }
}
