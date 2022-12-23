<?php

/**
 * Create user command.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Commands;

/**
 * Import dependencies.
 */
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Business\Users\CreateUser;
use Xsga\FilmAffinityApi\Commands\Validators\CreateUserValidator;
use Xsga\FilmAffinityApi\Dto\UserDto;

/**
 * CreateUserCommand class.
 */
#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user.',
    hidden: false,
    aliases: ['app:create-user']
)]
class CreateUserCommand extends Command
{
    /**
     * Add user service.
     *
     * @Inject
     */
    private CreateUser $createUser;

    /**
     * Validator.
     *
     * @Inject
     */
    private CreateUserValidator $validator;

    /**
     * SymfonyStyle.
     */
    private SymfonyStyle $display;

    /**
     * User e-mail.
     */
    private null|string $userEmail = null;

    /**
     * User password.
     */
    private null|string $userPass = null;

    /**
     * User password confirm.
     */
    private null|string $userPassR = null;

    /**
     * User role.
     */
    private null|string $userRole = null;

    /**
     * User status.
     */
    private null|string $userStatus = null;

    /**
     * Exit create user process.
     */
    private bool $exit = false;

    /**
     * Configure.
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a user.');
    }

    /**
     * Initialize command.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    /**
     * Interacts with the user.
     */
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

    /**
     * Executes command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->exit) {
            $this->display->warning('Create user command aborted');
            return Command::SUCCESS;
        }//end if

        if ($this->userPass !== $this->userPassR) {
            $this->display->error('Password confirmation error');
            return Command::FAILURE;
        }//end if

        if ($this->validator->validateUserExists($this->userEmail)) {
            $this->display->error('User "' . $this->userEmail . '" already exists');
            return Command::FAILURE;
        }//end if

        $userDto             = new UserDto();
        $userDto->email      = trim($this->userEmail);
        $userDto->password   = trim($this->userPass);
        $userDto->role       = trim($this->userRole);
        $userDto->enabled    = $this->userStatus === 'enabled' ? 1 : 0;
        $userDto->createDate = new DateTime();

        // Creates user.
        $userId = $this->createUser->create($userDto);

        $this->display->success("User created successfully (ID $userId)");

        return Command::SUCCESS;
    }
}
