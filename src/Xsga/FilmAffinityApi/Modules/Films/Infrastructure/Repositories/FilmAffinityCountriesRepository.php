<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetCountriesService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class FilmAffinityCountriesRepository implements CountriesRepository
{
    public function __construct(
        private LoggerInterface $logger,
        private UrlService $urlService,
        private HttpClientService $httpClientService,
        private GetCountriesService $getCountriesService
    ) {
    }

    /**
     * @return Country[]
     */
    public function getAll(): array
    {
        $advSearchFormUrl = $this->urlService->getAdvancedSearchFormUrl();
        $pageContent      = $this->httpClientService->getPageContent($advSearchFormUrl);

        $countries = $this->getCountriesService->get($pageContent);

        if (empty($countries)) {
            $this->logger->error('Error getting countries from FilmAffinity');
            return [];
        }

        return $countries;
    }

    public function get(string $code): ?Country
    {
        foreach ($this->getAll() as $country) {
            if ($country->code() === $code) {
                return $country;
            }
        }

        $this->logger->warning("Country with code '$code' not found");

        return null;
    }
}
