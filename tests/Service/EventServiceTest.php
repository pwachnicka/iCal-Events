<?php

namespace App\Tests\Service;

use ICal\ICal;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use App\Service\EventService;
use App\Service\FileManagerService;
use PHPUnit\Framework\MockObject\MockObject;

class EventServiceTest extends TestCase
{

    public function test_should_return_array_of_contents_for_existing_ical_file()
    {
        $icalFileName = 'icalFile.ical';

        /** @var ICal&MockObject $iCalMock */
        $iCalMock = $this->createMock(ICal::class);
        $iCalMock
            ->method('events')
            ->willReturn([
                new EventMock(
                    'b056d561bed8090cb0249a482065c59e@abcd.com',
                    new \DateTime('2024-01-30'),
                    new \DateTime('2024-03-28'),
                    'Summary'
                ),
                new EventMock(
                    'a051e561bfs8254cb0249a482145r59e@jgudk.com',
                    new \DateTime('2024-02-01'),
                    new \DateTime('2024-02-28'),
                    'Lorem ipsum'
                )
            ]);
        $iCalMock
            ->method('initFile')
            ->with($this->equalTo($icalFileName));

        $iCalMock
            ->method('iCalDateToDateTime')
            ->will($this->returnArgument(0));

        $loggerMock = $this->createMock(LoggerInterface::class);
        $fileManagerMock = $this->createMock(FileManagerService::class);

        $eventService = new EventService($iCalMock, $loggerMock, $fileManagerMock);

        $eventDetails = $eventService->getEventDetails($icalFileName);

        $this->assertIsArray($eventDetails);
        $this->assertEquals(2, count($eventDetails));
        $this->assertContains([
            'id' => 'b056d561bed8090cb0249a482065c59e@abcd.com',
            'start' => '2024-01-30',
            'end' => '2024-03-28',
            'summary' => 'Summary'
        ], $eventDetails);

        $this->assertContains([
            'id' => 'a051e561bfs8254cb0249a482145r59e@jgudk.com',
            'start' => '2024-02-01',
            'end' => '2024-02-28',
            'summary' => 'Lorem ipsum'
        ], $eventDetails);
    }
}
