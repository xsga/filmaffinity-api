<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Parser;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Psr\Log\LoggerInterface;

abstract class AbstractParser
{
    public function __construct(
        protected LoggerInterface $logger,
        protected DOMDocument $content
    ) {
    }

    public function init(string $pageContent): void
    {
        libxml_use_internal_errors(true);

        $this->content->loadHtml(mb_convert_encoding($pageContent, 'HTML-ENTITIES', 'UTF-8'));

        libxml_use_internal_errors(false);
    }

    protected function getData(string $query, bool $outArray = true): array|DOMNodeList
    {
        $domXpath = new DOMXPath($this->content);

        $data = $domXpath->query($query);

        if ($outArray) {
            $out = [];

            for ($i = 0; $i < count($data); $i++) {
                $out[] = trim($data[$i]->nodeValue);
            }
        } else {
            $out = $data;
        }

        return $out;
    }
}
