<?php 

namespace App\Controller;

use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController {

    #[Route('/api/events', methods: ['GET'])]
    public function getEvents(EventService $eventService) : Response {
        return $this->json($eventService->getEventsDetail($this->getParameter('app.events_link')));
    }
}