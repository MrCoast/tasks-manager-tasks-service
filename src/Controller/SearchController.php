<?php

namespace App\Controller;

use App\Entity\Priority;
use App\Entity\State;
use App\Entity\Task;
use App\Service\Search\RequestProcessorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search", name="search_")
 */
class SearchController
{
    /**
     * @var RequestProcessorInterface
     */
    private $requestProcessor;

    /**
     * @param RequestProcessorInterface $requestProcessor
     */
    public function __construct(RequestProcessorInterface $requestProcessor)
    {
        $this->requestProcessor = $requestProcessor;
    }

    /**
     * @Route("/by-state/{title}", name="search_by_state", methods={"GET"})
     *
     * @param State $state
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchByState(State $state, Request $request): JsonResponse
    {
        $criteria = ['state' => $state];

        return $this->requestProcessor->process($request, Task::class, $criteria);
    }

    /**
     * @Route("/by-priority/{title}", name="search_by_priority", methods={"GET"})
     *
     * @param Priority $priority
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchByPriority(Priority $priority, Request $request): JsonResponse
    {
        $criteria = ['priority' => $priority];

        return $this->requestProcessor->process($request, Task::class, $criteria);
    }

    /**
     * @Route("/by-title/{title}", name="search_by_title", methods={"GET"})
     *
     * @param string $title
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchByTitle(string $title, Request $request): JsonResponse
    {
        $criteria = ['title' => $title];

        return $this->requestProcessor->process($request, Task::class, $criteria);
    }
}
