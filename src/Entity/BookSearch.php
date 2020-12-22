<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class BookSearch {


    /**
     * @var int|null
     */
    private $maxYear;

    /**
     * @var int|null
     * @Assert\Range(min=1500)
     */
    private $minYear;

    /**
     * @var ArrayCollection
     */
    private $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    /**
     * @param int|null $maxYear
     * @return BookSearch
     */
    public function setMaxYear(?int $maxYear): BookSearch
    {
        $this->maxYear = $maxYear;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxYear(): ?int
    {
        return $this->maxYear;
    }

    /**
     * @param int|null $minYear
     * @return BookSearch
     */
    public function setMinYear(?int $minYear): BookSearch
    {
        $this->minYear = $minYear;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinYear(): ?int
    {
        return $this->minYear;
    }

    /**
     * @return ArrayCollection
     */
    public function getGenres(): ArrayCollection
    {
        return $this->genres;
    }

    /**
     * @param ArrayCollection $genres
     */
    public function setGenres(ArrayCollection $genres): void
    {
        $this->genres = $genres;
    }


}