<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat extends User
{

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToOne(mappedBy: 'candidat', cascade: ['persist', 'remove'])]
    private ?CandidatInfo $candidatInfo = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: IntresstedOffre::class)]
    private Collection $intresstedOffres;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Entretien::class)]
    private Collection $entretiens;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: IntresstedCandidats::class)]
    private Collection $intresstedCandidats;

    public function __construct()
    {
        $this->intresstedOffres = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
        $this->intresstedCandidats = new ArrayCollection();
    }

    public function getNom(): ?string
    {
        return strtoupper($this->nom);
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return ucfirst($this->prenom);
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCandidatInfo(): ?CandidatInfo
    {
        return $this->candidatInfo;
    }

    public function setCandidatInfo(CandidatInfo $candidatInfo): static
    {
        // set the owning side of the relation if necessary
        if ($candidatInfo->getCandidat() !== $this) {
            $candidatInfo->setCandidat($this);
        }

        $this->candidatInfo = $candidatInfo;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getNom().' '.$this->getPrenom();
    }

    public function __toString() : string
    {
        return $this->getFullName();
    }

    /**
     * @return Collection<int, IntresstedOffre>
     */
    public function getIntresstedOffres(): Collection
    {
        return $this->intresstedOffres;
    }

    public function addIntresstedOffre(IntresstedOffre $intresstedOffre): static
    {
        if (!$this->intresstedOffres->contains($intresstedOffre)) {
            $this->intresstedOffres->add($intresstedOffre);
            $intresstedOffre->setCandidat($this);
        }

        return $this;
    }

    public function removeIntresstedOffre(IntresstedOffre $intresstedOffre): static
    {
        if ($this->intresstedOffres->removeElement($intresstedOffre)) {
            // set the owning side to null (unless already changed)
            if ($intresstedOffre->getCandidat() === $this) {
                $intresstedOffre->setCandidat(null);
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
            $entretien->setCandidat($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): static
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getCandidat() === $this) {
                $entretien->setCandidat(null);
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
            $intresstedCandidat->setCandidat($this);
        }

        return $this;
    }

    public function removeIntresstedCandidat(IntresstedCandidats $intresstedCandidat): static
    {
        if ($this->intresstedCandidats->removeElement($intresstedCandidat)) {
            // set the owning side to null (unless already changed)
            if ($intresstedCandidat->getCandidat() === $this) {
                $intresstedCandidat->setCandidat(null);
            }
        }

        return $this;
    }
}
