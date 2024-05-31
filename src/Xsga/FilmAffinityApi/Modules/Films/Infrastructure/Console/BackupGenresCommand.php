<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Console;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\BackupGenresService;

#[AsCommand(
    name: 'app:backup-genres',
    description: 'Backup genres from FilmAffinity.',
    hidden: false,
    aliases: ['app:backup-genres']
)]
final class BackupGenresCommand extends Command
{
    #[Inject]
    private BackupGenresService $backupGenresService;

    private SymfonyStyle $display;

    protected function configure(): void
    {
        $this->setHelp('This command allows you to backup genres from FilmAffinity.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('BACKUP-GENRES command');
        $this->display->text('Use this command to backup genres from FilmAffinity.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->backupGenresService->get()) {
            $this->display->error('Genres backup error');
            return Command::FAILURE;
        }

        $this->display->success("Genres backup generated successfully");

        return Command::SUCCESS;
    }
}
