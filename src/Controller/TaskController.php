<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/task", name="task_")
 */
class TaskController
{
    private $taskRepository;

    private $serializer;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;

        $circularReferenceCallback = function ($object, $format, $context) {
            return $object->getId();
        };
        $dateCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime
                ? $innerObject->format(\DateTime::ISO8601)
                : '';
        };
        $innerObjectTitleCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) use (&$innerObjectTitleCallback) {
            if ($innerObject instanceof Collection) {
                return $innerObject->map(function ($collectionItem) use ($innerObjectTitleCallback, $outerObject, $attributeName, $format, $context) {
                    return $innerObjectTitleCallback($collectionItem, $outerObject, $attributeName, $format, $context);
                });
            }

            return method_exists($innerObject, 'getTitle')
                ? $innerObject->getTitle()
                : '';
        };
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => $circularReferenceCallback,
            AbstractNormalizer::CALLBACKS => [
                'tags' => $innerObjectTitleCallback,
                'priority' => $innerObjectTitleCallback,
                'state' => $innerObjectTitleCallback,
                'createdAt' => $dateCallback,
                'updatedAt' => $dateCallback,
            ],
        ];
        $this->serializer = new Serializer([new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext)], [new JsonEncoder()]);
    }

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list()
    {
        $tasks = $this->taskRepository->findAll();
        $data = $this->serializer->normalize($tasks);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
