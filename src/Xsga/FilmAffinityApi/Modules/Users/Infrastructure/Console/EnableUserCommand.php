<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\GetUserByEmailService;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\UpdateUserStatusService;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\UserEmail;

#[AsCommand(
    name: 'app:enable-user',
    description: 'Enables a user.',
    hidden: false,
    aliases: ['app:enable-user']
)]
final class EnableUserCommand extends Command
{
    private SymfonyStyle $display;
    private string $userEmail = '';

    #[Inject]
    private UpdateUserStatusService $userStatusService;

    #[Inject]
    private GetUserByEmailService $getUserByEmailService;

    protected function configure(): void
    {
        $this->setHelp('This command allows you to enable a user.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('ENABLE-USER command');
        $this->display->text('Use this command to enable a user.');

        $this->userEmail = $this->display->ask(
            'Enter user e-mail',
            null,
            function (?string $email) {
                $valueObject = new UserEmail($email === null ? '' : $email);
                return $valueObject->value();
            }
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->getUserByEmailService->get($this->userEmail);

        $this->userStatusService->set($user->userId, true);

        $this->display->success("User $this->userEmail enabled successfully");

        return Command::SUCCESS;
    }
}
