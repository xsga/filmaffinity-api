<?php

/**
 * Add user command.
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
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Xsga\FilmAffinityApi\Business\Users\AddUser;
use Xsga\FilmAffinityApi\Dto\UserDto;

/**
 * AddUserCommand class.
 */
#[AsCommand(
    name: 'app:add-user',
    description: 'Creates a new user.',
    hidden: false,
    aliases: ['app:add-user']
)]
class AddUserCommand extends Command
{
    /**
     * Add user service.
     * 
     * @Inject
     * @var AddUser
     * 
     * @access private
     */
    private $addUser;

    /**
     * Configure.
     *
     * @return void
     *
     * @access protected
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a user...');
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
        $helper = $this->getHelper('question');
        
        // User e-mail question.
        $questionEmail = new Question('Enter user e-mail: ');
        $questionEmail->setValidator(function($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('E-mail not valid');
            }//end if    
            return $answer;
        });
        $questionEmail->setMaxAttempts(3);
        
        // User password question.
        $questionPassword = new Question('Enter user pasword: ');
        $questionPassword->setValidator(function($answer) {
            // TODO: password format.
            if (strlen($answer) < 8) {
                throw new RuntimeException('Password not valid');
            }//end if    
            return $answer;
        });
        $questionPassword->setMaxAttempts(3);
        $questionPassword->setHidden(true);
        $questionPassword->setHiddenFallback(false);

        // User role question.
        $questionRole = new ChoiceQuestion('Choise a role: ', ['user', 'admin'], 0);
        $questionRole->setErrorMessage('Role %s is invalid');

        // User enabled question.
        $questionStatus = new ChoiceQuestion('Choise status: ', ['enabled', 'disabled'], 0);
        $questionStatus->setErrorMessage('Status %s is invalid');
        
        $userEmail    = $helper->ask($input, $output, $questionEmail);
        $userPassword = $helper->ask($input, $output, $questionPassword);
        $userRole     = $helper->ask($input, $output, $questionRole);
        $userStatus   = $helper->ask($input, $output, $questionStatus);

        $userDto             = new UserDto;
        $userDto->email      = trim($userEmail);
        $userDto->password   = trim($userPassword);
        $userDto->role       = trim($userRole);
        $userDto->enabled    = $userStatus === 'enabled' ? 1 : 0;
        $userDto->createDate = new DateTime();

        $userId = $this->addUser->add($userDto);

        return Command::SUCCESS;
    }
}
