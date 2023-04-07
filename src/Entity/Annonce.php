<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $isvisible = null;

    #[ORM\OneToOne(mappedBy: 'annonce', cascade: ['persist', 'remove'])]
    private ?Acquisition $acquisition = null;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Commentary::class)]
    private Collection $commentary;

    public function __construct()
    {
        $this->commentary = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function isIsVisible(): ?bool
    {
        return $this->isvisible;
    }

    public function setIsVisible(bool $isvisible): self
    {
        $this->isvisible = $isvisible;

        return $this;
    }

    public function getAcquisition(): ?Acquisition
    {
        return $this->acquisition;
    }

    public function setAcquisition(Acquisition $acquisition): self
    {
        // set the owning side of the relation if necessary
        if ($acquisition->getAnnonce() !== $this) {
            $acquisition->setAnnonce($this);
        }

        $this->acquisition = $acquisition;

        return $this;
    }

    /**
     * @return Collection<int, Commentary>
     */
    public function getCommentary(): Collection
    {
        return $this->commentary;
    }

    public function addCommentary(Commentary $commentary): self
    {
        if (!$this->commentary->contains($commentary)) {
            $this->commentary->add($commentary);
            $commentary->setAnnonce($this);
        }

        return $this;
    }

    public function removeCommentary(Commentary $commentary): self
    {
        if ($this->commentary->removeElement($commentary)) {
            // set the owning side to null (unless already changed)
            if ($commentary->getAnnonce() === $this) {
                $commentary->setAnnonce(null);
            }
        }

        return $this;
    }

    
}
