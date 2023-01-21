<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use App\Traits\SoftDeleteable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @Vich\Uploadable()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Book
{
    use SoftDeleteable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private ?string $filename = null;

    /**
     * @Assert\Image(
     *     mimeTypes="image/jpeg"
     * )
     * @Vich\UploadableField(mapping="book_image", fileNameProperty="filename")
     */
    private ?\Symfony\Component\HttpFoundation\File\File $imageFile = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $year = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $author_last = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $publisher = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime|\DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $author_first = null;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="books")
     */
    private \Doctrine\Common\Collections\Collection|array $genres;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime|\DateTimeInterface|null $updated_at = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $collection = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $volume = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $storage = null;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?int $isbn = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lendedto = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="books")
     */
    private ?\App\Entity\User $user = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getAuthorlast(): ?string
    {
        return $this->author_last;
    }

    public function setAuthorlast(?string $author_last): self
    {
        $this->author_last = $author_last;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(?string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAuthorFirst(): ?string
    {
        return $this->author_first;
    }

    public function setAuthorFirst(string $author_first): self
    {
        $this->author_first = $author_first;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addBook($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeBook($this);
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): Book
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): Book
    {
        $this->filename = $filename;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(?string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function setVolume(?int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(?string $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getIsbn(): ?int
    {
        return $this->isbn;
    }

    public function setIsbn(?int $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getLendedTo(): ?string
    {
        return $this->lendedto;
    }

    public function setLendedTo(?string $lendedto): self
    {
        $this->lendedto = $lendedto;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }



}
