<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/", name="list", methods={"GET"})
     */
    public function list()
    {
        $tasks = $this->manager->getRepository(Task::class)->findAll();
        $data = $this->serializer->normalize($tasks);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
