<?php

namespace App\Service\Search;

use App\Service\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestProcessor implements RequestProcessorInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     */
    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param string $entityClass
     * @param array $criteria
     *
     * @return JsonResponse
     */
    public function process(Request $request, string $entityClass, array $criteria): JsonResponse
    {
        $limit = $request->query->get('limit', 20);
        $offset = $request->query->get('offset', 0);

        $orderBy = null;
        $tasks = $this->manager->getRepository($entityClass)->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );

        $data = $this->serializer->normalize($tasks);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
