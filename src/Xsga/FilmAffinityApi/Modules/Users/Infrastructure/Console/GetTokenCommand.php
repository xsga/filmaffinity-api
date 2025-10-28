<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\GetTokenDto;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\GetTokenService;

#[AsCommand(
    name: 'app:get-token',
    description: 'Creates a new token.',
    hidden: false,
    aliases: ['app:get-token']
)]
final class GetTokenCommand extends Command
{
    #[Inject]
    private GetTokenService $getTokenService;

    private SymfonyStyle $display;
    private string $userEmail = '';
    private string $userPassword = '';

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a new token.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->display = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->display->title('GET-TOKEN command');
        $this->display->text('Use this command to create a new token.');

        $this->userEmail    = (string)$this->display->ask('Enter user e-mail');
        $this->userPassword = (string)$this->display->askHidden('Enter password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->getTokenService->get($this->getTokenDto());

        $this->display->newLine();
        $this->display->text($token);
        $this->display->success("Token created successfully");

        return Command::SUCCESS;
    }

    private function getTokenDto(): GetTokenDto
    {
        $dto = new GetTokenDto();
        $dto->user     = $this->userEmail;
        $dto->password = $this->userPassword;

        return $dto;
    }
}
