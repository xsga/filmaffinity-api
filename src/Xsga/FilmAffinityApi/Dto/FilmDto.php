<?php

/**
 * FilmDto class.
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
namespace Xsga\FilmAffinityApi\Dto;

/**
 *
 */
class FilmDto
{
    /**
     * FilmAffinity film ID.
     *
     * @var integer
     *
     * @access public
     */
    public $filmAfinityId = 0;

    /**
     * Film title.
     *
     * @var string
     *
     * @access public
     */
    public $title = '';

    /**
     * Film original title.
     *
     * @var string
     *
     * @access public
     */
    public $originalTitle = '';

    /**
     * Release year.
     *
     * @var string
     *
     * @access public
     */
    public $year = '';

    /**
     * Film duration.
     *
     * @var string
     *
     * @access public
     */
    public $duration = '';

    /**
     * Cover URL.
     *
     * @var string
     *
     * @access public
     */
    public $coverUrl = '';

    /**
     * Cover filename.
     *
     * @var string
     *
     * @access public
     */
    public $coverFile = '';

    /**
     * Rating.
     *
     * @var string
     *
     * @access public
     */
    public $rating = '';

    /**
     * Country.
     *
     * @var string
     *
     * @access public
     */
    public $country = '';

    /**
     * Directors.
     *
     * @var array
     *
     * @access public
     */
    public $directors = array();

    /**
     * Screenplay.
     *
     * @var string
     *
     * @access public
     */
    public $screenplay = '';

    /**
     * Sountrack.
     *
     * @var string
     *
     * @access public
     */
    public $soundtrack = '';

    /**
     * Photography.
     *
     * @var string
     *
     * @access public
     */
    public $photography = '';

    /**
     * Cast.
     *
     * @var array
     *
     * @access public
     */
    public $cast = array();

    /**
     * Producer.
     *
     * @var string
     *
     * @access public
     */
    public $producer = '';

    /**
     * Genres.
     *
     * @var array
     *
     * @access public
     */
    public $genres = array();

    /**
     * Synopsis.
     *
     * @var string
     *
     * @access public
     */
    public $synopsis = '';
}
