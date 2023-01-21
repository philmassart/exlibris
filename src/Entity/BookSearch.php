<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


///**
// * @ORM\Entity(repositoryClass="App\Repository\BookSearchRepository")
// */
class BookSearch
{

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $author_last = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $title = null;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $storage = null;


    private \Doctrine\Common\Collections\ArrayCollection $genres;


//    /**
//     * @var int|null
//     */
//    private $maxYear;
//
//    /**
//     * @var int|null
//     * @Assert\Range(min=1500)
//     */
//    private $minYear;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getGenres(): ArrayCollection
    {
        return $this->genres;
    }

    public function setGenres(ArrayCollection $genres): void
    {
        $this->genres = $genres;
    }

    public function getAuthorlast(): ?string
    {
        return $this->author_last;
    }

    /**
     * @return $this
     */
    public function setAuthorlast(?string $author_last): self
    {
        $this->author_last = $author_last;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getStorage(): ?string
    {
        return $this->storage;
    }

    /**
     * @return $this
     */
    public function setStorage(?string $storage): self
    {
        $this->storage = $storage;

        return $this;
    }





//
//    /**
//     * @param int|null $maxYear
//     * @return BookSearch
//     */
//    public function setMaxYear(?int $maxYear): BookSearch
//    {
//        $this->maxYear = $maxYear;
//        return $this;
//    }
//
//
//
//    /**
//     * @return int|null
//     */
//    public function getMaxYear(): ?int
//    {
//        return $this->maxYear;
//    }
//
//    /**
//     * @param int|null $minYear
//     * @return BookSearch
//     */
//    public function setMinYear(?int $minYear): BookSearch
//    {
//        $this->minYear = $minYear;
//        return $this;
//    }
//
//    /**
//     * @return int|null
//     */
//    public function getMinYear(): ?int
//    {
//        return $this->minYear;
//    }


}