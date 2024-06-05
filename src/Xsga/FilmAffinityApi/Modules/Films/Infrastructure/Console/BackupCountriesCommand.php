<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Console;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\BackupCountriesService;

#[AsCommand(
    name: 'app:backup-countries',
    description: 'Backup countries from FilmAffinity.',
    hidden: false,
    aliases: ['app:backup-countries']
)]
final class BackupCountriesCommand extends Command
{
    #[Inject]
    private BackupCountriesService $backupCountriesService;

    private SymfonyStyle $display;

    protected function configure(): void
    {
        $this->setHelp('This command allows you to backup countries from FilmAffinity.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('BACKUP-COUNTRIES command');
        $this->display->text('Use this command to backup countries from FilmAffinity.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->backupCountriesService->get()) {
            $this->display->error('Countries backup error');
            return Command::FAILURE;
        }

        $this->display->success("Countries backup generated successfully");

        return Command::SUCCESS;
    }
}
