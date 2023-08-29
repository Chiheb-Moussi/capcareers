<?php

namespace App\Entity;

use App\Repository\EmployeurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeurRepository::class)]
class Employeur extends User
{
    #[ORM\Column(length: 255)]
    private ?string $societe = null;

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): static
    {
        $this->societe = $societe;

        return $this;
    }
}
