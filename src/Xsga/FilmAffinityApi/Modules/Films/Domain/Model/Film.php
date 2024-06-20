<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmDuration;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmId;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmPhotography;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmProducer;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmRating;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmScreenplay;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmSoundtrack;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmSynopsis;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmTitle;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmYear;

class Film
{
    private FilmId $id;
    private FilmTitle $title;
    private FilmTitle $originalTitle;
    private FilmYear $year;
    private FilmDuration $duration;
    private FilmRating $rating;
    private Country $country;
    private FilmScreenplay $screenplay;
    private FilmSoundtrack $soundtrack;
    private FilmPhotography $photography;
    private FilmProducer $producer;
    private FilmSynopsis $synopsis;
    private Cover $cover;

    /**
     * @var Actor[]
     */
    private array $cast = [];

    /**
     * @var Director[]
     */
    private array $directors = [];

    /**
     * @var Genre[]
     */
    private array $genres = [];

    /**
     * @var GenreTopic[]
     */
    private array $genreTopics = [];

    /**
     * @param Actor[]      $cast
     * @param Director[]   $directors
     * @param Genre[]      $genres
     * @param GenreTopic[] $genreTopics
     */
    public function __construct(
        int $id,
        string $title,
        string $originalTitle,
        int $year,
        int $duration,
        string $rating,
        Country $country,
        string $screenplay,
        string $soundtrack,
        string $photography,
        string $producer,
        string $synopsis,
        Cover $cover,
        array $cast,
        array $directors,
        array $genres,
        array $genreTopics
    )
    {
        $this->id            = new FilmId($id);
        $this->title         = new FilmTitle($title);
        $this->originalTitle = new FilmTitle($originalTitle);
        $this->year          = new FilmYear($year);
        $this->duration      = new FilmDuration($duration);
        $this->rating        = new FilmRating($rating);
        $this->country       = $country;
        $this->screenplay    = new FilmScreenplay($screenplay);
        $this->soundtrack    = new FilmSoundtrack($soundtrack);
        $this->photography   = new FilmPhotography($photography);
        $this->producer      = new FilmProducer($producer);
        $this->synopsis      = new FilmSynopsis($synopsis);
        $this->cover         = $cover;
        $this->cast          = $cast;
        $this->directors     = $directors;
        $this->genres        = $genres;
        $this->genreTopics   = $genreTopics;
    }

    public function id(): int
    {
        return $this->id->value();
    }

    public function title(): string
    {
        return $this->title->value();
    }

    public function originalTitle(): string
    {
        return $this->originalTitle->value();
    }

    public function year(): int
    {
        return $this->year->value();
    }

    public function duration(): int
    {
        return $this->duration->value();
    }

    public function rating(): string
    {
        return $this->rating->value();
    }

    public function country(): Country
    {
        return $this->country;
    }

    public function screenplay(): string
    {
        return $this->screenplay->value();
    }

    public function soundtrack(): string
    {
        return $this->soundtrack->value();
    }

    public function photography(): string
    {
        return $this->photography->value();
    }

    public function producer(): string
    {
        return $this->producer->value();
    }

    public function synopsis(): string
    {
        return $this->synopsis->value();
    }

    public function cover(): Cover
    {
        return $this->cover;
    }

    /**
     * @return Actor[]
     */
    public function cast(): array
    {
        return $this->cast;
    }

    /**
     * @return Director[]
     */
    public function directors(): array
    {
        return $this->directors;
    }

    /**
     * @return Genre[]
     */
    public function genres(): array
    {
        return $this->genres;
    }

    /**
     * @return GenreTopic[]
     */
    public function genreTopics(): array
    {
        return $this->genreTopics;
    }

}
