<?php

namespace App\Service\Serializer;

interface SerializerInterface
{
    public function serialize($data, string $format = 'json', array $context = []): string;
    public function normalize($data, string $format = 'json', array $context = []);
}
