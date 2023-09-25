<?php

namespace App\Entity;

use App\Repository\IntresstedOffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntresstedOffreRepository::class)]
class IntresstedOffre
{
    const STATUS_EN_ATTENTE = 'En attente';
    const STATUS_ACCEPTE = 'Accepté';
    const STATUS_REFUSE = 'Refusé';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'intresstedOffres')]
    private ?Candidat $candidat = null;

    #[ORM\ManyToOne(inversedBy: 'intresstedOffres')]
    private ?Offre $offre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

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

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): static
    {
        $this->offre = $offre;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getStatusHtml(): ?string
    {
        if(self::STATUS_EN_ATTENTE === $this->status) {
            return "<div class='badge bg-warning text-white text-lg'>$this->status</div>";
        }
        if(self::STATUS_ACCEPTE === $this->status) {
            return "<div class='badge bg-success text-white text-lg'>$this->status</div>";
        }
        return "<div class='badge bg-danger text-white text-lg'>$this->status</div>";
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
