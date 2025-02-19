<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoyageRepository;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date_depart = null;

    #[ORM\Column(length: 255)]
    private ?string $emplacement_client = null;

    #[ORM\ManyToOne(inversedBy: 'voyages')]
    private ?Ambulance $ambulance = null;

    // Getter for `id`
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and Setter for `date_depart`
    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    // Getter and Setter for `emplacement_client`
    public function getEmplacementClient(): ?string
    {
        return $this->emplacement_client;
    }

    public function setEmplacementClient(string $emplacement_client): static
    {
        $this->emplacement_client = $emplacement_client;
        return $this;
    }

    // Getter and Setter for `ambulance`
    public function getAmbulance(): ?Ambulance
    {
        return $this->ambulance;
    }

    public function setAmbulance(?Ambulance $ambulance): static
    {
        $this->ambulance = $ambulance;
        return $this;
    }

    // Method to format the date
    public function getFormattedDateDepart(): string
    {
        return $this->date_depart ? $this->date_depart->format('d/m/Y') : '';
    }
}