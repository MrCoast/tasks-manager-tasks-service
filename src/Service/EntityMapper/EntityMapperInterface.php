<?php

namespace App\Service\EntityMapper;

interface EntityMapperInterface
{
    public function map(array $definition): object;
}
