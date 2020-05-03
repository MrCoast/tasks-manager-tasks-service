<?php

namespace App\Service\Deserializer;

use App\Entity\Task;
use App\Service\EntityMapper\TaskMapper;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class TaskDeserializer implements DeserializerInterface
{
    /**
     * @var JsonDecode
     */
    private $decoder;

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
        $this->decoder = new JsonDecode(['json_decode_associative' => true]);
    }

    /**
     * @param string $jsonData
     *
     * @return Task
     */
    public function deserialize(string $jsonData): Task
    {
        $taskDefinition = $this->decoder->decode($jsonData, 'json');

        return $this->mapper->map($taskDefinition);
    }
}
