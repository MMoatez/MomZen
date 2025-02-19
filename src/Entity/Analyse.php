<?php

namespace App\Entity;

use App\Repository\AnalyseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnalyseRepository::class)]
class Analyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le risque de grossesse ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le risque de grossesse ne peut pas dépasser 255 caractères.")]
    private ?string $risqueGrosses = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "L'urgence médicale doit être spécifiée.")]
    private ?bool $urgence_medicale = null;

    #[ORM\ManyToOne(inversedBy: 'analyses')]
    #[Assert\NotNull(message: "Le dossier médical ne peut pas être nul.")]
    private ?DossierMedicall $dossier_medicale = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUrgence_medicale(): ?bool
    {
        return $this->urgence_medicale;
    }

    public function setUrgence_medicale(?bool $urgence_medicale): self
    {
        $this->urgence_medicale = $urgence_medicale;

        return $this;
    }
    public function getRisqueGrosses(): ?string
    {
        return $this->risqueGrosses;
    }

    public function setRisqueGrosses(string $risqueGrosses): static
    {
        $this->risqueGrosses = $risqueGrosses;

        return $this;
    }

    public function isUrgenceMedicale(): ?bool
    {
        return $this->urgence_medicale;
    }

    public function setUrgenceMedicale(bool $urgence_medicale): static
    {
        $this->urgence_medicale = $urgence_medicale;

        return $this;
    }

    public function getDossierMedicale(): ?DossierMedicall
    {
        return $this->dossier_medicale;
    }

    public function setDossierMedicale(?DossierMedicall $dossier_medicale): static
    {
        $this->dossier_medicale = $dossier_medicale;

        return $this;
    }
}
