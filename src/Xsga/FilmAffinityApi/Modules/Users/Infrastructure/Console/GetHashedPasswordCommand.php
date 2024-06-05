<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\UserPassword;

#[AsCommand(
    name: 'app:get-password',
    description: 'Creates a new hashed password.',
    hidden: false,
    aliases: ['app:get-password']
)]
final class GetHashedPasswordCommand extends Command
{
    private SymfonyStyle $display;
    private string $hashedPassword = '';

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a new hashed password.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('GET-HASHED-PASSWORD command');
        $this->display->text('Use this command to create a new hashed password.');

        $this->hashedPassword = $this->display->askHidden(
            'Enter password',
            function (?string $password) {
                $password = match (is_null($password)) {
                    true => '',
                    false => $password
                };
                $valueObject = new UserPassword($password);
                return $valueObject->value();
            }
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->display->newLine();
        $this->display->text($this->hashedPassword);
        $this->display->success("Hashed password created successfully");

        return Command::SUCCESS;
    }
}
