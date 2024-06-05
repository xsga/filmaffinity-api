<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\UpdateUserStatusService;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\UserEmail;

#[AsCommand(
    name: 'app:disable-user',
    description: 'Disables a user.',
    hidden: false,
    aliases: ['app:disable-user']
)]
final class DisableUserCommand extends Command
{
    private SymfonyStyle $display;
    private string $userEmail = '';

    #[Inject]
    private UpdateUserStatusService $userStatusService;

    protected function configure(): void
    {
        $this->setHelp('This command allows you to disable a user.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('DISABLE-USER command');
        $this->display->text('Use this command to disable a user.');

        $this->userEmail = $this->display->ask(
            'Enter user e-mail',
            null,
            function (?string $email) {
                $email = match (is_null($email)) {
                    true => '',
                    false => $email
                };
                $valueObject = new UserEmail($email);
                return $valueObject->value();
            }
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->userStatusService->set($this->userEmail, false);
        
        $this->display->success("User $this->userEmail disabled successfully");

        return Command::SUCCESS;
    }
}
