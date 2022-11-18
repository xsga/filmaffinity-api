<?php

/**
 * Change user status command.
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
use Xsga\FilmAffinityApi\Business\Users\ChangeUserStatus;
use Xsga\FilmAffinityApi\Commands\Validators\ChangeUserStatusValidator;

/**
 * ChangeUserStatusCommand class.
 */
#[AsCommand(
    name: 'app:user-status',
    description: 'Change user status.',
    hidden: false,
    aliases: ['app:user-status']
)]
class ChangeUserStatusCommand extends Command
{
    /**
     * Add user service.
     *
     * @Inject
     * @var ChangeUserStatus
     *
     * @access private
     */
    private $changeUserStatus;

    /**
     * Validator.
     *
     * @Inject
     * @var ChangeUserStatusValidator
     *
     * @access private
     */
    private $validator;

    /**
     * SymfonyStyle.
     *
     * @var SymfonyStyle
     *
     * @access private
     */
    private $display;

    /**
     * User e-mail.
     *
     * @var string
     *
     * @access private
     */
    private $userEmail = '';

    /**
     * User status.
     *
     * @var string
     *
     * @access private
     */
    private $userStatus = '';

    /**
     * Exit create user process.
     *
     * @var boolean
     *
     * @access private
     */
    private $exit = false;

    /**
     * Configure.
     *
     * @return void
     *
     * @access protected
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to enable a user.');
    }

    /**
     * Initialize command.
     *
     * @param InputInterface  $input  InputInterface instance.
     * @param OutputInterface $output OutputInterface instance.
     *
     * @return void
     *
     * @access protected
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    /**
     * Interacts with the user.
     *
     * @param InputInterface  $input  InputInterface instance.
     * @param OutputInterface $output OutputInterface instance.
     *
     * @return void
     *
     * @access protected
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('ENABLE-USER command');
        $this->display->text('Use this command to enable an existing user.');

        $this->userEmail  = $this->display->ask('Enter user e-mail', null, [$this->validator, 'validateEmail']);
        $this->userStatus = $this->display->choice('Choise a user status', ['enabled', 'disabled'], 0);
        $this->exit       = $this->display->confirm('Do you want to continue?', true);
    }

    /**
     * Executes command.
     *
     * @param InputInterface  $input  InputInterface instance.
     * @param OutputInterface $output OutputInterface instance.
     *
     * @return integer
     *
     * @access protected
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->exit) {
            $this->display->warning('Change user status command aborted');
            return Command::SUCCESS;
        }//end if

        if (!$this->validator->validateUserExists($this->userEmail)) {
            $this->display->error('User "' . $this->userEmail . '" not found');
            return Command::FAILURE;
        }//end if

        switch ($this->userStatus) {
            case 'enabled':
                $userStatus = 1;
                break;
            case 'disabled':
                $userStatus = 0;
        }//end switch

        if ($this->changeUserStatus->change($this->userEmail, $userStatus)) {
            $this->display->success('User status changed successfully');
            return Command::SUCCESS;
        }//end if

        $this->display->error('Error changing user status');
        return Command::FAILURE;
    }
}
