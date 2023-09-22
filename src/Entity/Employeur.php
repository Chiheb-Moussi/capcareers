<?php

namespace App\Entity;

use App\Repository\EmployeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeurRepository::class)]
class Employeur extends User
{
    #[ORM\Column(length: 255)]
    private ?string $societe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $offre = null;

    #[ORM\OneToMany(mappedBy: 'employeur', targetEntity: Offre::class, orphanRemoval: true)]
    private Collection $offres;

    #[ORM\OneToMany(mappedBy: 'employeur', targetEntity: IntresstedCandidats::class)]
    private Collection $intresstedCandidats;

    #[ORM\OneToMany(mappedBy: 'employeur', targetEntity: Entretien::class)]
    private Collection $entretiens;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->intresstedCandidats = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    public function getOffre(): ?string
    {
        return $this->offre;
    }

    public function setOffre(?string $offre): static
    {
        $this->offre = $offre;

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setEmployeur($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getEmployeur() === $this) {
                $offre->setEmployeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, IntresstedCandidats>
     */
    public function getIntresstedCandidats(): Collection
    {
        return $this->intresstedCandidats;
    }

    public function addIntresstedCandidat(IntresstedCandidats $intresstedCandidat): static
    {
        if (!$this->intresstedCandidats->contains($intresstedCandidat)) {
            $this->intresstedCandidats->add($intresstedCandidat);
            $intresstedCandidat->setEmployeur($this);
        }

        return $this;
    }

    public function removeIntresstedCandidat(IntresstedCandidats $intresstedCandidat): static
    {
        if ($this->intresstedCandidats->removeElement($intresstedCandidat)) {
            // set the owning side to null (unless already changed)
            if ($intresstedCandidat->getEmployeur() === $this) {
                $intresstedCandidat->setEmployeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entretien>
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): static
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens->add($entretien);
            $entretien->setEmployeur($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): static
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getEmployeur() === $this) {
                $entretien->setEmployeur(null);
            }
        }

        return $this;
    }
}
