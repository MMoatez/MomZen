<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $numTel = null;

    #[ORM\Column]
    private ?string $genre = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'idpatient')]
    private ?Rendezvous $rendezvous = null;

    /**
     * @var Collection<int, Reclamation>
     */
    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'user')]
    private Collection $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

   // #[ORM\Column]
   // private bool $isVerified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
  
     */



    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantir que chaque utilisateur a au moins ROLE_USER

        // ajouter d'autres rôles si l'utilisateur a des rôles supplémentaires
        if (in_array('ROLE_USER', $this->roles)) {
            $roles[] = 'ROLE_USER';
        }
        if (in_array('ROLE_ADMIN', $this->roles)) {
            $roles[] = 'ROLE_ADMIN';
        }
        if (in_array('ROLE_DOCTEUR', $this->roles)) {
            $roles[] = 'ROLE_DOCTEUR';
        }
        if (in_array('ROLE_CHAUFFEUR', $this->roles)) {
            $roles[] = 'ROLE_CHAUFFEUR';
        }

        return array_unique($roles);
    }












    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): static
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function isGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
/*
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    } */

public function getRendezvous(): ?Rendezvous
{
    return $this->rendezvous;
}

public function setRendezvous(?Rendezvous $rendezvous): static
{
    $this->rendezvous = $rendezvous;

    return $this;
}

/**
 * @return Collection<int, Reclamation>
 */
public function getReclamations(): Collection
{
    return $this->reclamations;
}

public function addReclamation(Reclamation $reclamation): static
{
    if (!$this->reclamations->contains($reclamation)) {
        $this->reclamations->add($reclamation);
        $reclamation->setUser($this);
    }

    return $this;
}

public function removeReclamation(Reclamation $reclamation): static
{
    if ($this->reclamations->removeElement($reclamation)) {
        // set the owning side to null (unless already changed)
        if ($reclamation->getUser() === $this) {
            $reclamation->setUser(null);
        }
    }

    return $this;
}
}
