<?php

/**
 * XpathCons.
 *
 * This file contains the FilmAffinity XPath queries to parse HTML pages.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Business\Parser;

/**
 * Class XpathCons.
 */
final class XpathCons
{
    /**
     * XPath query to get various film data.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_VARIOUS = "//dl[@class = 'movie-info']/dd[not(@class) and not(@itemprop)]";

    /**
     * XPath query to get film title.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_TITLE = "//h1[@id = 'main-title']/span[@itemprop = 'name']";

    /**
     * XPath query to get film release date.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_RELEASE_DATE = "//dd[@itemprop = 'datePublished']";

    /**
     * XPath query to get film duration.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_DURATION = "//dd[@itemprop = 'duration']";

    /**
     * XPath query to get film directors.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_DIRECTORS = "//dd[@class = 'directors']/div[@class = 'credits']/span[@itemprop = 'director']/a[@itemprop = 'url']/span[@itemprop = 'name']";

    /**
     * XPath query to get film actors.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_ACTORS = "//dd[@class = 'card-cast']/div[@class = 'credits']/span[@itemprop = 'actor']/a[@itemprop = 'url']/span[@itemprop = 'name']";

    /**
     * XPath query to get film producers.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_PRODUCERS = "//dd[@class = 'card-producer']/div[@class = 'credits']/span";

    /**
     * XPath query to get film genres.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_GENRES = "//dd[@class = 'card-genres']//a";

    /**
     * XPath query to get film rating.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_RATING = "//div[@id = 'movie-rat-avg']";

    /**
     * XPath query to get film synopsis.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_SYNOPSIS = "//dd[@class = '' and @itemprop = 'description']";

    /**
     * XPath query to get film cover.
     *
     * @var string
     *
     * @access public
     */
    public const FILM_COVER = "//a[@class = 'lightbox']";

    /**
     * XPath query to get if simple search has one or more results.
     *
     * @var string
     *
     * @access public
     */
    public const SEARCH_TYPE = "//meta[@property = 'og:title']";

    /**
     * XPath query to get film title from search results.
     *
     * @var string
     *
     * @access public
     */
    public const SEARCH_TITLE = "//div[@class = 'mc-title']/a";

    /**
     * XPath query to get film ID if search has one result.
     *
     * @var string
     *
     * @access public
     */
    public const SEARCH_ID_SINGLE = "//meta[@property = 'og:url']";

    /**
     * XPath query to get simple search results.
     *
     * @var string
     *
     * @access public
     */
    public const SEARCH_RESULTS = "//div[contains(@class, 'se-it')]";

    /**
     * XPath query to get film year om simple search results.
     *
     * @var string
     *
     * @access public
     */
    public const SEARCH_YEAR = "//div[contains(@class, 'ye-w')]";

    /**
     * XPath query to get film ID in search results.
     *
     * @var string
     *
     * @access public
     */
    public const SEARCH_ID = "//div[contains(@class, 'movie-card')]";

    /**
     * XPath query to get advanced search results.
     *
     * @var string
     *
     * @access public
     */
    public const SEARCH_ADV = "//div[contains(@class, 'adv-search-item')]";
}
