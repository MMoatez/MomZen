<?php

// src/Entity/Ambulance.php
namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Ambulance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'chauffeur_id', referencedColumnName: 'id')]
    private $chauffeur;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'immatriculation ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^\d{3} tunis \d{4}$/",
        message: "L'immatriculation doit être au format '123 tunis 4567'."
    )]
    private $immatriculation;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "La marque ne peut pas être vide.")]
    #[Assert\Length(
        max: 15,
        maxMessage: "Le titre ne doit pas dépasser 15 caractères."
    )]
    private $marque;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le modèle ne peut pas être vide.")]
    #[Assert\Length(
        max: 15,
        maxMessage: "Le titre ne doit pas dépasser 15 caractères."
    )]
    private $modele;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChauffeur(): ?User
    {
        return $this->chauffeur;
    }

    public function setChauffeur(?User $chauffeur): self
    {
        $this->chauffeur = $chauffeur;
        return $this;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;
        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;
        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;
        return $this;
    }
}