<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 */
class Outing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le nom de la sortie ne peut être nulle")
     *
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Expression(
     *     "this.getStartDate() > new DateTime()",
     *      message="La date doit être supérieur à aujourd\'hui")
     */
    private $startDate;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $limitDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Range(min="2",max="150",notInRangeMessage="Le nombre max de place doit être compris entre {{min}} et {{max}}")
     */
    private $nbInscription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $outingInfo;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="outings")
     */
    private $attendees;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="organizedOutings")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $organizer;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $Place;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $duration;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }


    public function getLimitDate(): ?\DateTimeInterface
    {
        return $this->limitDate;
    }

    public function setLimitDate(\DateTimeInterface $limitDate): self
    {
        $this->limitDate = $limitDate;

        return $this;
    }

    public function getNbInscription(): ?int
    {
        return $this->nbInscription;
    }

    public function setNbInscription(int $nbInscription): self
    {
        $this->nbInscription = $nbInscription;

        return $this;
    }

    public function getOutingInfo(): ?string
    {
        return $this->outingInfo;
    }

    public function setOutingInfo(?string $outingInfo): self
    {
        $this->outingInfo = $outingInfo;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    /**
     * @param ArrayCollection $attendees
     */
    public function setAttendees(ArrayCollection $attendees): void
    {
        $this->attendees = $attendees;
    }

    public function addAttendee(User $attendee): self
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees[] = $attendee;
        }

        return $this;
    }

    public function removeAttendee(User $attendee): self
    {
        $this->attendees->removeElement($attendee);

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

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

    public function getPlace(): ?Place
    {
        return $this->Place;
    }

    public function setPlace(?Place $Place): self
    {
        $this->Place = $Place;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
