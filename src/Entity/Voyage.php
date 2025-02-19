<?php

// src/Entity/Voyage.php
namespace App\Entity;

use App\Entity\Ambulance;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Ambulance::class)]
    #[ORM\JoinColumn(name: 'ambulance_id', referencedColumnName: 'id')]
    #[Assert\NotBlank(message: "Veuillez sélectionner une ambulance.")]
    private $ambulance;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "Veuillez sélectionner une date de départ.")]
    #[Assert\GreaterThan("now", message: "La date de départ doit être dans le futur.")]
    private $date_depart;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Veuillez indiquer l'emplacement du client.")]
    private $emplacement_client;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmbulance(): ?Ambulance
    {
        return $this->ambulance;
    }

    public function setAmbulance(?Ambulance $ambulance): self
    {
        $this->ambulance = $ambulance;
        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    public function getEmplacementClient(): ?string
    {
        return $this->emplacement_client;
    }

    public function setEmplacementClient(string $emplacement_client): self
    {
        $this->emplacement_client = $emplacement_client;
        return $this;
    }
}