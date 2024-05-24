<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Commands;

use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Business\Users\CreateUser;
use Xsga\FilmAffinityApi\Commands\Validators\CreateUserValidator;
use Xsga\FilmAffinityApi\Dto\UserDto;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user.',
    hidden: false,
    aliases: ['app:create-user']
)]
class CreateUserCommand extends Command
{
    /**
     * @Inject
     */
    private CreateUser $createUser;

    /**
     * @Inject
     */
    private CreateUserValidator $validator;

    private SymfonyStyle $display;

    private null|string $userEmail = null;

    private null|string $userPass = null;

    private null|string $userPassR = null;

    private null|string $userRole = null;

    private null|string $userStatus = null;

    private bool $exit = false;

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a user.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('CREATE-USER command');
        $this->display->text('Use this command to create a new user.');

        $this->userEmail  = $this->display->ask('Enter user e-mail', null, [$this->validator, 'validateEmail']);
        $this->userPass   = $this->display->askHidden('Enter user pasword', [$this->validator, 'validatePassword']);
        $this->userPassR  = $this->display->askHidden('Repeat user pasword');
        $this->userRole   = $this->display->choice('Choise a user role', ['user', 'admin'], 0);
        $this->userStatus = $this->display->choice('Choise a user status', ['enabled', 'disabled'], 0);
        $this->exit       = $this->display->confirm('Do you want to continue?', true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->exit) {
            $this->display->warning('Create user command aborted');
            return Command::SUCCESS;
        }

        if ($this->userPass !== $this->userPassR) {
            $this->display->error('Password confirmation error');
            return Command::FAILURE;
        }

        if ($this->validator->validateUserExists($this->userEmail)) {
            $this->display->error('User "' . $this->userEmail . '" already exists');
            return Command::FAILURE;
        }

        $userDto             = new UserDto();
        $userDto->email      = trim($this->userEmail);
        $userDto->password   = trim($this->userPass);
        $userDto->role       = trim($this->userRole);
        $userDto->enabled    = $this->userStatus === 'enabled' ? 1 : 0;
        $userDto->createDate = new DateTime();

        $userId = $this->createUser->create($userDto);

        $this->display->success("User created successfully (ID $userId)");

        return Command::SUCCESS;
    }
}
