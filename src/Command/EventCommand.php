<?php

namespace App\Command;

use App\Service\EventService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

#[AsCommand(
    name: 'app:get-events-detail',
    description: 'Returns details of events.',
    hidden: false,
)]
class EventCommand extends Command
{

    public function __construct(
        private EventService $eventService,
        private ContainerBagInterface $params,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $events = $this->eventService->getEventDetailsAsJson($this->params->get('app.events_link'));

        $output->writeln($events);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command returns event details for the specified ical file link.');
    }
}
