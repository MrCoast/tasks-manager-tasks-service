<?php

namespace App\Service\Deserializer;

interface DeserializerInterface
{
    public function deserialize(string $jsonData): object;
}
