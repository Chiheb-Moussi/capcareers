<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $autreExigences = null;

    #[ORM\ManyToMany(targetEntity: Skill::class)]
    private Collection $skills;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $demarrage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profil = null;

    #[ORM\Column(nullable: true)]
    private ?float $tjm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\ManyToOne]
    private ?Secteur $secteur = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employeur $employeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeContrat = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaire = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $autreAvantages = null;

    #[ORM\ManyToOne]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: IntresstedOffre::class)]
    private Collection $intresstedOffres;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->intresstedOffres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAutreExigences(): ?string
    {
        return $this->autreExigences;
    }

    public function setAutreExigences(?string $autreExigences): static
    {
        $this->autreExigences = $autreExigences;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    public function getDemarrage(): ?\DateTimeInterface
    {
        return $this->demarrage;
    }

    public function setDemarrage(?\DateTimeInterface $demarrage): static
    {
        $this->demarrage = $demarrage;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(?string $profil): static
    {
        $this->profil = $profil;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSecteur(): ?Secteur
    {
        return $this->secteur;
    }

    public function setSecteur(?Secteur $secteur): static
    {
        $this->secteur = $secteur;

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

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?string $typeContrat): static
    {
        $this->typeContrat = $typeContrat;

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

    public function getAutreAvantages(): ?string
    {
        return $this->autreAvantages;
    }

    public function setAutreAvantages(?string $autreAvantages): static
    {
        $this->autreAvantages = $autreAvantages;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();;

        return $this;
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
            $intresstedOffre->setOffre($this);
        }

        return $this;
    }

    public function removeIntresstedOffre(IntresstedOffre $intresstedOffre): static
    {
        if ($this->intresstedOffres->removeElement($intresstedOffre)) {
            // set the owning side to null (unless already changed)
            if ($intresstedOffre->getOffre() === $this) {
                $intresstedOffre->setOffre(null);
            }
        }

        return $this;
    }
}
