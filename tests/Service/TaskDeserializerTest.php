<?php

namespace App\Tests\Service;

use App\Entity\Task;
use App\Service\Deserializer\TaskDeserializer;
use App\Service\EntityMapper\TaskMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskDeserializerTest extends TestCase
{
    /**
     * @var TaskDeserializer
     */
    private $taskDeserializer;

    private const TEST_TASK_TITLE = 'breakfast';

    public function setUp()
    {
        $taskMapper = $this->getTaskMapperMock();

        $this->taskDeserializer = new TaskDeserializer($taskMapper);
    }

    public function testDeserialize()
    {
        $jsonData = sprintf('{"title": "%s"}', self::TEST_TASK_TITLE);
        $task = $this->taskDeserializer->deserialize($jsonData);

        $this->assertEquals(self::TEST_TASK_TITLE, $task->getTitle());
    }

    private function getTaskMapperMock(): MockObject
    {
        $taskMapper = $this->createMock(TaskMapper::class);
        $taskMapper
            ->method('map')
            ->willReturn(new Task(self::TEST_TASK_TITLE));

        return $taskMapper;
    }
}
