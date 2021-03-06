<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdsRepository")
 */
class Ads
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({"default"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=65536)
     * @Groups({"default"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups({"default"})
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="ads", orphanRemoval=true)
     */
    private $photos;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="category")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="ads")
     * @Groups({"default"})
     */
    private $region;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;



    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->isActive =true;
    }

    public function getId()
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getAuthor(): ?Person
    {
        return $this->author;
    }

    public function setAuthor(?Person $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAds($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getAds() === $this) {
                $photo->setAds(null);
            }
        }

        return $this;
    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }


}
