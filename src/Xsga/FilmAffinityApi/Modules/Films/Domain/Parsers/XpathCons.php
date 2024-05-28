<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers;

final class XpathCons
{
    public const FILM_VARIOUS = "//dd[not(@class) and not(@itemprop)]";
    public const FILM_TITLE = "//h1[@id = 'main-title']/span[@itemprop = 'name']";
    public const FILM_RELEASE_DATE = "//dd[@itemprop = 'datePublished']";
    public const FILM_DURATION = "//dd[@itemprop = 'duration']";
    public const FILM_DIRECTORS = "//dd[@class = 'directors']/div[@class = 'credits']/span[@itemprop = 'director']/a[@itemprop = 'url']/span[@itemprop = 'name']";
    public const FILM_ACTORS = "//li[@itemprop = 'actor']";
    public const FILM_PRODUCERS = "//dd[@class = 'card-producer']/div[@class = 'credits']/span";
    public const FILM_GENRES = "//dd[@class = 'card-genres']//a";
    public const FILM_RATING = "//div[@id = 'movie-rat-avg']";
    public const FILM_SYNOPSIS = "//dd[@class = '' and @itemprop = 'description']";
    public const FILM_COVER = "//a[@class = 'lightbox']";
    public const SEARCH_TYPE = "//meta[@property = 'og:title']";
    public const SEARCH_TITLE = "//div[@class = 'mc-title']/a";
    public const SEARCH_ID_SINGLE = "//meta[@property = 'og:url']";
    public const SEARCH_RESULTS = "//div[contains(@class, 'se-it')]";
    public const SEARCH_YEAR = "//div[contains(@class, 'ye-w')]";
    public const SEARCH_YEAR_ADV = "//span[contains(@class, 'mc-year')]";
    public const SEARCH_ID = "//div[contains(@class, 'movie-card')]";
    public const SEARCH_ADV = "//div[contains(@class, 'adv-search-item')]";
}
