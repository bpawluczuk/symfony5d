<?php

declare(strict_types=1);

namespace App\Organization\Ui\Cli;

use App\Organization\Application\CreateOrganizationCommand;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateOrganization extends Command
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct('organization:new');
        $this->commandBus = $commandBus;
    }

    public function configure(): void
    {
        $this->setDescription('Create new Organization');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Add new Organization');

        $name = $io->ask('How do you call your Organization?');

        $this->commandBus->handle(new CreateOrganizationCommand(
            $name,
            'http://example.com',
        ));

        $io->writeln(sprintf('Organization %s was added', $name));

        return 0;
    }
}