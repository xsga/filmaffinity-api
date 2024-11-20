<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Exceptions\ErrorNotFoundException;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories\ErrorsRepository;

final class GetError
{
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
        $error = $this->repository->getError($code);

        if ($error === null) {
            $message = "Error with code '$code' not found";
            $this->logger->error($message);
            throw new ErrorNotFoundException($message, 1023, null, [1 => $code]);
        }

        return $error;
    }
}
