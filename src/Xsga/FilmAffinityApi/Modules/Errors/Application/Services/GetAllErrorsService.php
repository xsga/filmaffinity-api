<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Application\Services;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Mappers\ErrorToErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories\ErrorsRepository;

final class GetAllErrorsService
{
    public function __construct(
        private ErrorsRepository $repository,
        private ErrorToErrorDto $mapper
    ) {
    }

    /**
     * @return ErrorDto[]
     */
    public function get(): array
    {
        $errors = $this->repository->getAllErrors();

        return $this->mapper->convertArray($errors);
    }
}
