<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use DateTime;
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
     * @Assert\Length(max="50", maxMessage="Le nom de la sortie ne peut contenir plus de 50 caractères")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z\s]*$/",
     *     message="Le nom de la sortie ne peut contenir seulement des lettres")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Expression(
     *     "this.getStartDate() > this.getNow()",
     *      message="La date doit être postérieur à aujourd'hui")
     */
    private $startDate;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\Expression(
     *     "this.getLimitDate() < this.getStartDate()",
     *     message="La date doit être antérieur à la date de début de sortie"
     * )
     */
    private $limitDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min="2",max="150",notInRangeMessage="Le nombre max de place doit être compris entre 2 et 150")
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
     */
    private $organizer;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $Place;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez insérer une durée")
     * @Assert\Positive(message="La durée doit être positive")
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

    public function getNow(): DateTime
    {
        return new DateTime();
    }
}
