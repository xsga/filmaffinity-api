<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services;

interface HttpClientService
{
    public function getPageContent(string $url): string;
}
