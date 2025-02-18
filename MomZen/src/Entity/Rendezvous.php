<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'rendezvous')]
    private Collection $idpatient;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'rendezvous')]
    private Collection $idmedecin;

    public function __construct()
    {
        $this->idpatient = new ArrayCollection();
        $this->idmedecin = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getIdpatient(): Collection
    {
        return $this->idpatient;
    }

    public function addIdpatient(User $idpatient): static
    {
        if (!$this->idpatient->contains($idpatient)) {
            $this->idpatient->add($idpatient);
            $idpatient->setRendezvous($this);
        }

        return $this;
    }

    public function removeIdpatient(User $idpatient): static
    {
        if ($this->idpatient->removeElement($idpatient)) {
            // set the owning side to null (unless already changed)
            if ($idpatient->getRendezvous() === $this) {
                $idpatient->setRendezvous(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdmedecin(): Collection
    {
        return $this->idmedecin;
    }

    public function addIdmedecin(User $idmedecin): static
    {
        if (!$this->idmedecin->contains($idmedecin)) {
            $this->idmedecin->add($idmedecin);
            $idmedecin->setRendezvous($this);
        }

        return $this;
    }

    public function removeIdmedecin(User $idmedecin): static
    {
        if ($this->idmedecin->removeElement($idmedecin)) {
            // set the owning side to null (unless already changed)
            if ($idmedecin->getRendezvous() === $this) {
                $idmedecin->setRendezvous(null);
            }
        }

        return $this;
    }
}
