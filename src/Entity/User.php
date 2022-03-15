<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("pseudo", message="Ce pseudo est déjà utilisé")
 * @UniqueEntity("email", message="Cet email est déjà utilisé")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *     min=6,
     *     minMessage="Mot de passe trop court")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="Votre prénom ne peut contenir au maximum 50 caractères")
     * @Assert\Regex(
     *     pattern="/^[a-z ,.'-]+$/i",
     *     message="Prénom incorrect")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="Votre nom ne peut contenir au maximum 50 caractères")
     * @Assert\Regex(
     *     pattern="/^[a-z ,.'-]+$/i",
     *     message="Nom incorrect")
     */
    private $lastName;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity=Outing::class, mappedBy="attendees")
     */
    private $outings;

    /**
     * @ORM\OneToMany(targetEntity=Outing::class, mappedBy="organizer")
     */
    private $organizedOutings;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     *
     * @ORM\Column(type="string", length=20)
     * @Assert\Regex(
     *     pattern="/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/",
     *     message="Le format de téléphone est incorrect")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=5,
     *     max = 50,
     *     maxMessage="Le pseudo ne peut dépasser 50 caractères",
     *     minMessage="Le pseudo doit contenir au moins 5 caractères")
     */
    private $pseudo;

    public function __construct()
    {
        $this->outings = new ArrayCollection();
        $this->organizedOutings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOutings(): Collection
    {
        return $this->outings;
    }

    public function addOuting(Outing $outing): self
    {
        if (!$this->outings->contains($outing)) {
            $this->outings[] = $outing;
            $outing->addAttendee($this);
        }

        return $this;
    }

    public function removeOuting(Outing $outing): self
    {
        if ($this->outings->removeElement($outing)) {
            $outing->removeAttendee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOrganizedOutings(): Collection
    {
        return $this->organizedOutings;
    }

    public function addOrganizedOuting(Outing $organizedOuting): self
    {
        if (!$this->organizedOutings->contains($organizedOuting)) {
            $this->organizedOutings[] = $organizedOuting;
            $organizedOuting->setOrganizer($this);
        }

        return $this;
    }

    public function removeOrganizedOuting(Outing $organizedOuting): self
    {
        if ($this->organizedOutings->removeElement($organizedOuting)) {
            // set the owning side to null (unless already changed)
            if ($organizedOuting->getOrganizer() === $this) {
                $organizedOuting->setOrganizer(null);
            }
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }
}
