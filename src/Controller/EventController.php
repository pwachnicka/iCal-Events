<?php

namespace App\Controller;

use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{

    #[Route('/api/events', methods: ['GET'])]
    public function getEvents(EventService $eventService): JsonResponse
    {
        return JsonResponse::fromJsonString($eventService->getEventDetailsAsJson($this->getParameter('app.events_link')));
    }
}
