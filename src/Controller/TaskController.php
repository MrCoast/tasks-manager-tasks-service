<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task", name="task_")
 */
class TaskController
{
    private $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list()
    {
        $tasks = $this->taskRepository->findAll();

        $data = [];
        foreach ($tasks as $task) {
            $tags = [];

            foreach ($task->getTags() as $tag) {
                $tags[] = $tag->getTitle();
            }

            $data[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'tags' => $tags,
                'priority' => $task->getPriority()->getTitle(),
                'state' => $task->getState()->getTitle(),
                'description' => $task->getDescription(),
                'createdAt' => $task->getCreatedAt(),
                'updatedAt' => $task->getUpdatedAt(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
