<?php

namespace App\Entity;

use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DossierMedicalRepository::class)]
class DossierMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date de création est requise.")]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le nombre de semaines de grossesse est requis.")]
    #[Assert\Positive(message: "Le nombre de semaines de grossesse doit être positif.")]
    private ?int $grosses_semaine = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Les symptômes sont requis.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Les symptômes ne doivent pas dépasser {{ limit }} caractères."
    )]
    private ?string $symptotes = null;

    #[ORM\ManyToOne(inversedBy: 'dossierMedicals')]
    #[Assert\NotNull(message: "Le patient est requis.")]
    private ?User $patient = null;

    /**
     * @var Collection<int, Analyse>
     */
    #[ORM\OneToMany(targetEntity: Analyse::class, mappedBy: 'dossier_medicale', cascade: ['remove'])]
    private Collection $analyses;

    public function __construct()
    {
        $this->analyses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): static
    {
        $this->datecreation = $datecreation;
        return $this;
    }

    public function getGrossesSemaine(): ?int
    {
        return $this->grosses_semaine;
    }
    public function getgrosses_semaine(): ?int
    {
        return $this->grosses_semaine;
    }
    public function setgrosses_semaine(int $grosses_semaine): static
    {
        $this->grosses_semaine = $grosses_semaine;
        return $this;
    }
    public function setGrossesSemaine(int $grosses_semaine): static
    {
        $this->grosses_semaine = $grosses_semaine;
        return $this;
    }
    public function getSymptotes(): ?string
    {
        return $this->symptotes;
    }

    public function setSymptotes(string $symptotes): static
    {
        $this->symptotes = $symptotes;
        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;
        return $this;
    }

    /**
     * @return Collection<int, Analyse>
     */
    public function getAnalyses(): Collection
    {
        return $this->analyses;
    }

    public function addAnalysis(Analyse $analysis): static
    {
        if (!$this->analyses->contains($analysis)) {
            $this->analyses->add($analysis);
            $analysis->setDossierMedicale($this);
        }
        return $this;
    }

    public function removeAnalysis(Analyse $analysis): static
    {
        if ($this->analyses->removeElement($analysis)) {
            if ($analysis->getDossierMedicale() === $this) {
                $analysis->setDossierMedicale(null);
            }
        }
        return $this;
    }
}