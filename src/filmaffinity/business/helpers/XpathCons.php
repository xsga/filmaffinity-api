<?php
/**
 * XpathCons.
 *
 * This file contains the XsgaFilmAffinity xPath queries to parse HTML pages.
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
namespace api\filmaffinity\business\helpers;

/**
 * Class XpathCons.
 */
class XpathCons
{
    
    /**
     * xPath query to get title from og_title property on meta info.
     *
     * @var string
     *
     * @access public
     */
    const OG_TITLE = "//meta[@property = 'og:title']";
    
    /**
     * xPath query to get title from class mc-title on <DIV> element.
     *
     * @var string
     *
     * @access public
     */
    const MC_TITLE = "//div[@class = 'mc-title']";
    
    /**
     * xPath query to get film URL from og:url property on meta info.
     *
     * @var string
     *
     * @access public
     */
    const OG_URL = "//meta[@property = 'og:url']";
    
    /**
     * xPath query to get movie-card class on <DIV> element.
     *
     * @var string
     *
     * @access public
     */
    const MOVIE_CARD = "//div[contains(@class, 'movie-card')]";
    
    /**
     * xPath query to get movie-info class on <DL> element.
     *
     * @var string
     */
    const MOVIE_INFO = "//dl[@class = 'movie-info']";
    
    /**
     * xPath query to get year from ye-w class on <DIV> element.
     *
     * @var string
     *
     * @access public
     */
    const YEA_W = "//div[@class = 'ye-w']";
    
    /**
     * xPath query to get title AKA class on <DT> or <DD> element.
     *
     * @var string
     *
     * @access public
     */
    const AKAS = "./dt[not(@class) or @class != 'akas'] | ./dd[not(@class) or @class != 'akas']";
    
    /**
     * xPath query to get lightbox class on <A> element.
     * 
     * @var string
     * 
     * @access public
     */
    const LIGHTBOX = "//a[@class = 'lightbox']";
    
    
}//end XpathCons class
