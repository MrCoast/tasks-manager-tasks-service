<?php

namespace App\Service\Deserializer;

use App\Entity\Task;
use App\Service\EntityMapper\TaskMapper;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class TaskDeserializer implements DeserializerInterface
{
    private $decoder;

    private $mapper;

    public function __construct(TaskMapper $mapper)
    {
        $this->mapper = $mapper;
        $this->decoder = new JsonDecode(['json_decode_associative' => true]);
    }

    public function deserialize(string $jsonData): Task
    {
        $taskDefinition = $this->decoder->decode($jsonData, 'json');

        return $this->mapper->map($taskDefinition);
    }
}
