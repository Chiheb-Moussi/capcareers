<?php

namespace App\Entity;

use App\Repository\CandidatInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: CandidatInfoRepository::class)]
class CandidatInfo
{


    const JUNIOR = 'Junior';
    const CONFIRME = 'Confirmé';
    const SENIOR = 'Senior';

    const CONTRAT_CDI = 'CDI';
    const CONTRAT_FREELANCE = 'Freelance';
    const CONTRAT_PORTAGE_SALARIAL = 'Portage salarial';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'candidatInfo', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Candidat $candidat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $disponibilite = null;

    #[ORM\Column(nullable: true)]
    private ?float $tjm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeProfile = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreExp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeContrat = null;

    #[ORM\OneToMany(mappedBy: 'candidatInfo', targetEntity: CandidatInfoSkill::class, orphanRemoval: true)]
    private Collection $candidatInfoSkills;

    #[ORM\OneToMany(mappedBy: 'candidatInfo', targetEntity: Experience::class, orphanRemoval: true)]
    private Collection $experiences;

    #[ORM\OneToMany(mappedBy: 'candidatInfo', targetEntity: Formation::class, orphanRemoval: true)]
    private Collection $formations;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCv = null;

    public function __construct()
    {
        $this->candidatInfoSkills = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->formations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(Candidat $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getDisponibilite(): ?\DateTimeInterface
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(?\DateTimeInterface $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getTjm(): ?float
    {
        return $this->tjm;
    }

    public function setTjm(?float $tjm): static
    {
        $this->tjm = $tjm;

        return $this;
    }

    public function getTypeProfile(): ?string
    {
        return $this->typeProfile;
    }

    public function setTypeProfile(?string $typeProfile): static
    {
        $this->typeProfile = $typeProfile;

        return $this;
    }

    public function getNombreExp(): ?int
    {
        return $this->nombreExp;
    }

    public function setNombreExp(?int $nombreExp): static
    {
        $this->nombreExp = $nombreExp;

        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?string $typeContrat): static
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    /**
     * @return Collection<int, CandidatInfoSkill>
     */
    public function getCandidatInfoSkills(): Collection
    {
        return $this->candidatInfoSkills;
    }

    public function addCandidatInfoSkill(CandidatInfoSkill $candidatInfoSkill): static
    {
        if (!$this->candidatInfoSkills->contains($candidatInfoSkill)) {
            $this->candidatInfoSkills->add($candidatInfoSkill);
            $candidatInfoSkill->setCandidatInfo($this);
        }

        return $this;
    }

    public function removeCandidatInfoSkill(CandidatInfoSkill $candidatInfoSkill): static
    {
        if ($this->candidatInfoSkills->removeElement($candidatInfoSkill)) {
            // set the owning side to null (unless already changed)
            if ($candidatInfoSkill->getCandidatInfo() === $this) {
                $candidatInfoSkill->setCandidatInfo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setCandidatInfo($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getCandidatInfo() === $this) {
                $experience->setCandidatInfo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setCandidatInfo($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getCandidatInfo() === $this) {
                $formation->setCandidatInfo(null);
            }
        }

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getDateCv(): ?\DateTimeInterface
    {
        return $this->dateCv;
    }

    public function setDateCv(?\DateTimeInterface $dateCv): static
    {
        $this->dateCv = $dateCv;

        return $this;
    }


}
