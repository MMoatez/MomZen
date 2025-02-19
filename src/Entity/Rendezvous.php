<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $date = null;

   /* #[ORM\Column(length: 255)]
    private ?string $adresse = null;*/
    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $adresse = null;


/*
    #[ORM\OneToMany(cascade: ['persist', 'remove'])]
    private ?User $idpatient = null;

    #[ORM\OneToMany(cascade: ['persist', 'remove'])]
    private ?User $idmedecin = null;
*/
#[ORM\ManyToOne(targetEntity: User::class, inversedBy: "rendezvous")]
#[ORM\JoinColumn(nullable: false)]
private ?User $idpatient = null;

#[ORM\ManyToOne(targetEntity: User::class, inversedBy: "consultations")]
#[ORM\JoinColumn(nullable: false)]
private ?User $idmedecin = null;





    #[ORM\Column(type: "boolean")]
private bool $domicile = false;


#[ORM\Column(type: "boolean", nullable: true)]
private ?bool $realise = null;


    public function __construct()
    {
        $this->date = new \DateTimeImmutable(); // ✅ This ensures the date is never null
    }
        
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }



    public function isDomicile(): bool
{
    return $this->domicile;
}

public function setDomicile(bool $domicile): static
{
    $this->domicile = $domicile;
    return $this;
}


    public function isRealise(): ?bool
    {
        return $this->realise;
    }

    public function setRealise(bool $realise): static
    {
        $this->realise = $realise;

        return $this;
    }
    public function getIdmedecin(): ?User
    {
        return $this->idmedecin;
    }
    
    public function setIdmedecin(?User $idmedecin): self
    {
        $this->idmedecin = $idmedecin;
    
        return $this;
    }
    
    public function getIdpatient(): ?User
    {
        return $this->idpatient;
    }
    
    public function setIdpatient(?User $idpatient): self
    {
        $this->idpatient = $idpatient;
    
        return $this;
    }
}
