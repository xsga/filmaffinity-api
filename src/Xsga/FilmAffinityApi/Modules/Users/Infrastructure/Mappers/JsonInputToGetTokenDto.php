<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\GetTokenDto;

final class JsonInputToGetTokenDto
{
    public function convert(array $data): GetTokenDto
    {
        $dto = new GetTokenDto();

        $dto->user     = (string)$data['user'];
        $dto->password = (string)$data['password'];

        return $dto;
    }
}
