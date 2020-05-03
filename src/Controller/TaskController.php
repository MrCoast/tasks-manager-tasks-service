<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task", name="task_")
 */
class TaskController
{
    private $manager;

    private $serializer;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="list", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $tasks = $this->manager->getRepository(Task::class)->findAll();
        $data = $this->serializer->normalize($tasks);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="get_one", requirements={"id"="\d+"}, methods={"GET"})
     *
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException
     */
    public function getOne(int $id): JsonResponse
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
}
