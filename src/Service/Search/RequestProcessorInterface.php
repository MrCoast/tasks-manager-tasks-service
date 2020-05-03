<?php

namespace App\Service\Search;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface RequestProcessorInterface
{
    public function process(Request $request, string $entityClass, array $criteria): JsonResponse;
}
