<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\Serializer\SerializerInterface;
use App\Service\Deserializer\DeserializerInterface;
use App\Service\EntityUpdater\EntityUpdaterInterface;
use App\Service\Search\RequestProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task", name="task_")
 */
class TaskController
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
     * @var DeserializerInterface
     */
    private $deserializer;

    /**
     * @var EntityUpdaterInterface
     */
    private $updater;

    /**
     * @var RequestProcessorInterface
     */
    private $requestProcessor;

    /**
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param DeserializerInterface $deserializer
     * @param EntityUpdaterInterface $updater
     * @param RequestProcessorInterface $requestProcessor
     */
    public function __construct(
        EntityManagerInterface $manager,
        SerializerInterface $serializer,
        DeserializerInterface $deserializer,
        EntityUpdaterInterface $updater,
        RequestProcessorInterface $requestProcessor
    ) {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->deserializer = $deserializer;
        $this->updater = $updater;
        $this->requestProcessor = $requestProcessor;
    }

    /**
     * @Route("", name="list", methods={"GET"})
     *
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="The limit of records returned"
     * )
     *
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     description="The offset of records returned"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of all tasks",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Task::class, groups={"full"}))
     *     )
     * )
     *
     * @SWG\Tag(name="tasks")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $criteria = [];

        return $this->requestProcessor->process($request, Task::class, $criteria);
    }

    /**
     * @Route("/{id}", name="get", requirements={"id"="\d+"}, methods={"GET"})
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The ID of a task"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns a task object",
     *     @Model(type=Task::class, groups={"full"})
     * )
     *
     * @SWG\Tag(name="tasks")
     *
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException
     */
    public function get(int $id): JsonResponse
    {
        $task = $this->manager->getRepository(Task::class)->find($id);

        if ($task === null) {
            throw new NotFoundHttpException(sprintf('Task #%d not found', $id));
        }

        $data = $this->serializer->normalize($task);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The ID of a task"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Deletes a task",
     *     @SWG\Schema(type="object")
     * )
     *
     * @SWG\Tag(name="tasks")
     *
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException
     */
    public function delete(int $id): JsonResponse
    {
        $task = $this->manager->getRepository(Task::class)->find($id);

        if ($task === null) {
            throw new NotFoundHttpException(sprintf('Task #%d not found', $id));
        }

        $task = $this->manager->remove($task);
        $this->manager->flush();

        return new JsonResponse(['status' => 'OK'], Response::HTTP_OK);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="A task object that needs to be added",
     *     required=true,
     *     @Model(type=Task::class, groups={"full"})
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Creates a task",
     *     @SWG\Schema(type="object")
     * )
     *
     * @SWG\Tag(name="tasks")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $jsonData = $request->getContent();
        $task = $this->deserializer->deserialize($jsonData);

        $this->manager->persist($task);
        $this->manager->flush();

        return new JsonResponse(['status' => 'OK'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update", requirements={"id"="\d+"}, methods={"PUT"})
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The ID of a task"
     * )
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="A task object that needs to be updated",
     *     required=true,
     *     @Model(type=Task::class, groups={"full"})
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Updates a task",
     *     @SWG\Schema(type="object")
     * )
     *
     * @SWG\Tag(name="tasks")
     *
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $jsonData = $request->getContent();

        /**
         * @var Task
         */
        $existingTask = $this->manager->getRepository(Task::class)->find($id);

        if ($existingTask === null) {
            throw new NotFoundHttpException(sprintf('Task #%d not found', $id));
        }

        /**
         * @var Task
         */
        $newTask = $this->deserializer->deserialize($jsonData);

        $this->updater->update($existingTask, $newTask);
        $this->manager->flush();

        return new JsonResponse(['status' => 'OK'], Response::HTTP_OK);
    }
}
