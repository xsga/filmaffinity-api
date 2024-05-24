<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Commands;

use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Business\Users\ChangeUserStatus;
use Xsga\FilmAffinityApi\Commands\Validators\ChangeUserStatusValidator;

#[AsCommand(
    name: 'app:user-status',
    description: 'Change user status.',
    hidden: false,
    aliases: ['app:user-status']
)]
class ChangeUserStatusCommand extends Command
{
    /**
     * @Inject
     */
    private ChangeUserStatus $changeUserStatus;

    /**
     * @Inject
     */
    private ChangeUserStatusValidator $validator;

    private SymfonyStyle $display;

    private string $userEmail = '';

    private string $userStatus = '';

    private bool $exit = false;

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
        $this->display->text('Use this command to enable an existing user.');

        $this->userEmail  = $this->display->ask('Enter user e-mail', null, [$this->validator, 'validateEmail']);
        $this->userStatus = $this->display->choice('Choise a user status', ['enabled', 'disabled'], 0);
        $this->exit       = $this->display->confirm('Do you want to continue?', true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->exit) {
            $this->display->warning('Change user status command aborted');
            return Command::SUCCESS;
        }

        if (!$this->validator->validateUserExists($this->userEmail)) {
            $this->display->error('User "' . $this->userEmail . '" not found');
            return Command::FAILURE;
        }

        switch ($this->userStatus) {
            case 'enabled':
                $userStatus = 1;
                break;
            case 'disabled':
                $userStatus = 0;
        }

        if ($this->changeUserStatus->change($this->userEmail, $userStatus)) {
            $this->display->success('User status changed successfully');
            return Command::SUCCESS;
        }

        $this->display->error('Error changing user status');
        return Command::FAILURE;
    }
}
