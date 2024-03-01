<?php

namespace App\Service;

use ICal\ICal;
use Psr\Log\LoggerInterface;

class EventService
{

    public function __construct(
        private ICal $ical,
        private LoggerInterface $logger,
        private FileManagerService $fileManagerService,
    ) {
    }

    public function getEventDetails(string $eventLink): array
    {

        $this->logger->info('Start processing events from the file: {eventLink}', [
            'eventLink' => $eventLink,
        ]);

        try {
            $this->ical->initFile($eventLink);
        } catch (\Throwable $e) {
            $this->logger->error('An error occurred during processing the iCal file: {error}', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        $events = $this->ical->events();
        $this->logger->info('Downloaded {eventsNumber} events, start downloading details', [
            'eventsNumber' => count($events),
        ]);

        $eventDetails = [];
        foreach ($events as $event) {
            $dtstart = $this->ical->iCalDateToDateTime($event->dtstart);
            $dtend = $this->ical->iCalDateToDateTime($event->dtend);

            $eventDetails[] = [
                'id' => $event->uid,
                'start' => $dtstart->format('Y-m-d'),
                'end' => $dtend->format('Y-m-d'),
                'summary' => $event->summary,
            ];
        }

        $this->logger->info('Completed the download of the details. {eventsNumber} lines have been processed', [
            'eventsNumber' => count($eventDetails),
        ]);

        return $eventDetails;
    }

    public function getEventDetailsAsJson(string $eventLink): string
    {

        $eventDetails = json_encode($this->getEventDetails($eventLink));
        $this->fileManagerService->save($eventDetails);

        return $eventDetails;
    }
}
