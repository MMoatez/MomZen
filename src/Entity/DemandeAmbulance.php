<?php
// src/Entity/DemandeAmbulance.php
namespace App\Entity;

use App\Entity\User;
use App\Entity\Ambulance;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DemandeAmbulanceRepository;
#[ORM\Entity(repositoryClass: DemandeAmbulanceRepository::class)]
class DemandeAmbulance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Ambulance::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $ambulance;

    #[ORM\Column(type: 'string', length: 20)]
    private $statut = 'en_attente'; // Statuts possibles : en_attente, confirmée, annulée

    #[ORM\Column(type: 'datetime')]
    private $dateCreation;

    public function __construct()
{
    $this->dateCreation = new \DateTime(); // Initialisation automatique
}

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }
}