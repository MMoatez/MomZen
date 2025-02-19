<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ordonnance = null;

    #[ORM\ManyToOne(targetEntity: Dossiermedical::class)]
    #[ORM\JoinColumn(name: 'id_dossier', referencedColumnName: 'id', nullable: false)]
    private ?Dossiermedical $id_dossier = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_med', referencedColumnName: 'id', nullable: false)]
    private ?User $id_med = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdonnance(): ?string
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?string $ordonnance): static
    {
        $this->ordonnance = $ordonnance;

        return $this;
    }

    public function getDossiermedical(): ?Dossiermedical
    {
        return $this->id_dossier;
    }

    public function setDossiermedical(Dossiermedical $dossiermedical): self
    {
        $this->id_dossier = $dossiermedical;
        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->id_med;
    }

    public function setMedecin(User $medecin): self
    {
        $this->id_med = $medecin;
        return $this;
    }
}
