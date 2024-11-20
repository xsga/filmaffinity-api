<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

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

        $this->content->loadHtml(htmlspecialchars_decode(htmlentities($pageContent, ENT_COMPAT, 'UTF-8'), ENT_QUOTES));

        libxml_use_internal_errors(false);
    }

    protected function getData(string $query): DOMNodeList
    {
        return $this->getDOMData($query);
    }

    protected function getDataArray(string $query): array
    {
        return $this->convertToArray($this->getDOMData($query));
    }

    private function getDOMData(string $query): DOMNodeList
    {
        $domXpath = new DOMXPath($this->content);

        return $domXpath->query($query);
    }

    private function convertToArray(DOMNodeList $data): array
    {
        $out = [];

        for ($i = 0; $i < count($data); $i++) {
            $out[] = trim($data[$i]->nodeValue);
        }

        return $out;
    }
}
