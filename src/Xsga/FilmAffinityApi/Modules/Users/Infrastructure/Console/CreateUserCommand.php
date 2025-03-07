<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console;

use DI\Attribute\Inject;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\CreateUserService;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\UserEmail;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\UserPassword;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\JsonInputToCreateUserDto;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user.',
    hidden: false,
    aliases: ['app:create-user']
)]
final class CreateUserCommand extends Command
{
    private SymfonyStyle $display;
    private array $dataEntry = [];

    #[Inject]
    private CreateUserService $createUser;

    #[Inject]
    private JsonInputToCreateUserDto $mapper;

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

        $this->dataEntry['email'] = $this->display->ask(
            'Enter user e-mail',
            null,
            function (?string $email) {
                $valueObject = new UserEmail($email === null ? '' : $email);
                return $valueObject->value();
            }
        );
        $this->dataEntry['password'] = $this->display->askHidden(
            'Enter user password',
            function (?string $password) {
                $valueObject = new UserPassword($password === null ? '' : $password);
                return $password;
            }
        );
        $this->dataEntry['passwordR'] = $this->display->askHidden(
            'Repeat user password',
            function (?string $pass) {
                $passwordR = $pass === null ? '' : $pass;
                if ($passwordR === $this->dataEntry['password'] && $passwordR !== '') {
                    return $passwordR;
                }
                throw new RuntimeException('Password confirmation error');
            }
        );
        $this->dataEntry['continue'] = $this->display->confirm('Do you want to continue?', true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->dataEntry['continue']) {
            $this->display->warning('Create user command aborted');
            return Command::SUCCESS;
        }

        $createUserDto = $this->mapper->convert($this->dataEntry);
        $userId        = $this->createUser->create($createUserDto);

        $this->display->success("User created successfully (ID $userId)");

        return Command::SUCCESS;
    }
}
