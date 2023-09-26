<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntretienRepository::class)]
class Entretien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
    private ?Candidat $candidat = null;

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
    private ?Employeur $employeur = null;


    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\OneToMany(mappedBy: 'entretien', targetEntity: EntretienDate::class)]
    private Collection $entretienDates;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    private ?Offre $offre = null;

    public function __construct()
    {
        $this->entretienDates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getEmployeur(): ?Employeur
    {
        return $this->employeur;
    }

    public function setEmployeur(?Employeur $employeur): static
    {
        $this->employeur = $employeur;

        return $this;
    }



    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, EntretienDate>
     */
    public function getEntretienDates(): Collection
    {
        return $this->entretienDates;
    }

    public function addEntretienDate(EntretienDate $entretienDate): static
    {
        if (!$this->entretienDates->contains($entretienDate)) {
            $this->entretienDates->add($entretienDate);
            $entretienDate->setEntretien($this);
        }

        return $this;
    }

    public function removeEntretienDate(EntretienDate $entretienDate): static
    {
        if ($this->entretienDates->removeElement($entretienDate)) {
            // set the owning side to null (unless already changed)
            if ($entretienDate->getEntretien() === $this) {
                $entretienDate->setEntretien(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): static
    {
        $this->offre = $offre;

        return $this;
    }
}
