<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\DeleteUserByEmailService;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\UserEmail;

#[AsCommand(
    name: 'app:delete-user',
    description: 'Deletes a user.',
    hidden: false,
    aliases: ['app:delete-user']
)]
final class DeleteUserCommand extends Command
{
    private SymfonyStyle $display;
    private string $userEmail = '';
    private bool $continue = false;

    #[Inject]
    private DeleteUserByEmailService $deleteUserByEmailService;

    protected function configure(): void
    {
        $this->setHelp('This command allows you to delete a user.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('DELETE-USER command');
        $this->display->text('Use this command to delete a user.');

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
        $this->continue = $this->display->confirm('Do you want to continue?', true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->continue) {
            $this->display->warning('Delete user command aborted');
            return Command::SUCCESS;
        }

        $this->deleteUserByEmailService->delete($this->userEmail);

        $this->display->success("User $this->userEmail deleted successfully");

        return Command::SUCCESS;
    }
}
