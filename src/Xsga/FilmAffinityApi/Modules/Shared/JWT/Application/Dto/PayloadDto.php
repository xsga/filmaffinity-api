<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Dto;

final class PayloadDto
{
    public int $iat = 0;
    public int $exp = 0;
    public array $content = [];
}
