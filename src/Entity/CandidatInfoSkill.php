<?php

namespace App\Entity;

use App\Repository\CandidatInfoSkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatInfoSkillRepository::class)]
class CandidatInfoSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Skill $skill = null;

    #[ORM\ManyToOne(inversedBy: 'candidatInfoSkills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CandidatInfo $candidatInfo = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): static
    {
        $this->skill = $skill;

        return $this;
    }

    public function getCandidatInfo(): ?CandidatInfo
    {
        return $this->candidatInfo;
    }

    public function setCandidatInfo(?CandidatInfo $candidatInfo): static
    {
        $this->candidatInfo = $candidatInfo;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }
}
