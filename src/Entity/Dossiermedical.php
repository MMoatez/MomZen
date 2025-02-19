<?php

namespace App\Entity;

use App\Repository\DossiermedicalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossiermedicalRepository::class)]
class Dossiermedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $historique = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?User $idpatient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHistorique(): ?string
    {
        return $this->historique;
    }

    public function setHistorique(string $historique): static
    {
        $this->historique = $historique;

        return $this;
    }

    public function getIdpatient(): ?User
    {
        return $this->idpatient;
    }

    public function setIdpatient(?User $idpatient): static
    {
        $this->idpatient = $idpatient;

        return $this;
    }
}
