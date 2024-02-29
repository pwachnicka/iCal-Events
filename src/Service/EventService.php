<?php

namespace App\Service;

use ICal\ICal;
use Psr\Log\LoggerInterface;

class EventService {

    public function __construct(
        private ICal $ical,
        private LoggerInterface $logger
    ) {}

    public function getEventsDetail(string $eventLink): array {

        $this->logger->info('Start processing events from the file: {eventLink}', [
            'eventLink' => $eventLink,
        ]);

        try {
            $this->ical->initFile($eventLink);
        } catch (\Throwable $e) {
            $this->logger->error('An error occurred: {error}', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
        
        $events = $this->ical->events();
        $this->logger->info('Downloaded {eventsNumber} events, start downloading details', [
            'eventsNumber' => count($events),
        ]);

        $eventsDetails = [];
        foreach ($events as $event) {
            $dtstart = $this->ical->iCalDateToDateTime($event->dtstart);
            $dtend = $this->ical->iCalDateToDateTime($event->dtend);
            
            $eventsDetails[] = [
                'id' => $event->uid,
                'start' => $dtstart->format('Y-m-d'),
                'end' => $dtend->format('Y-m-d'),
                'summary' => $event->summary,
            ];
        }

        $this->logger->info('Completed the download of the details. {eventsNumber} lines have been processed', [
            'eventsNumber' => count($eventsDetails),
        ]);

        return $eventsDetails;
    }
}