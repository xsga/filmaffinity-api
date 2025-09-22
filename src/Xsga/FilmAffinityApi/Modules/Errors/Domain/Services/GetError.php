<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Exceptions\ErrorNotFoundException;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories\ErrorsRepository;

final class GetError
{
    private const int ERROR_ACCOUNT_NOT_FOUND = 1023;

    public function __construct(
        private LoggerInterface $logger,
        private ErrorsRepository $repository,
    ) {
    }

    public function byCode(int $code): ?Error
    {
        return $this->repository->getError($code);
    }

    public function byCodeWithErrorWhenNotFound(int $code): Error
    {
        $error = $this->byCode($code);

        if ($error === null) {
            $message = "Error with code '$code' not found";
            $this->logger->error($message);
            throw new ErrorNotFoundException($message, self::ERROR_ACCOUNT_NOT_FOUND, null, [1 => $code]);
        }

        return $error;
    }
}
