<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\CoverFileName;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\CoverUrl;

final class Cover
{
    private CoverUrl $url;
    private CoverFileName $fileName;

    public function __construct(string $url, string $fileName)
    {
        $this->url      = new CoverUrl($url);
        $this->fileName = new CoverFileName($fileName);
    }

    public function url(): string
    {
        return $this->url->value();
    }

    public function fileName(): string
    {
        return $this->fileName->value();
    }
}
