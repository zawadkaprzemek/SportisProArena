<?php

namespace App\Entity;

use App\Repository\UserRepository;
use ReflectionClass;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"uuid"}, message="Uuid must be unique")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    const PLAYER_TYPE=1;
    const MANAGER_TYPE=2;
    const ADMIN_TYPE=3;
    const ADMIN_MASTER_TYPE=4;
    const PARTNER_TYPE=5;

    const ROLES=[
        self::PLAYER_TYPE=>'ROLE_PLAYER',
        self::MANAGER_TYPE=>'ROLE_MANAGER',
        self::ADMIN_TYPE=>'ROLE_ADMIN',
        self::ADMIN_MASTER_TYPE=>'ROLE_ADMIN_MASTER',
        self::PARTNER_TYPE=>'ROLE_PARTNER',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image='assets/images/profile-default.jpg';

    /**
     * @ORM\Column(type="boolean")
     */
    private $data_consent;

    /**
     * @ORM\Column(type="boolean")
     */
    private $marketing_consent;

    /**
     * @ORM\Column(type="string", length=12, unique=true)
     */
    private $uuid;

    /**
     * @ORM\ManyToMany(targetEntity=Position::class, inversedBy="players",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="players")
     * @ORM\JoinColumn(nullable=true)
     */
    private $club;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $yearbook;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $userType;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $trainingUnits=0;

    /**
     * @ORM\OneToMany(targetEntity=PlayerManager::class, mappedBy="player")
     */
    private $player;

    private $oldPassword;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="user")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity=TrainingSession::class, mappedBy="buyer")
     */
    private $purchasedTrainingSessions;

    /**
     * @ORM\OneToMany(targetEntity=TrainingSession::class, mappedBy="player")
     */
    private $trainingSessions;

    /**
     * @ORM\OneToMany(targetEntity=TrainingConfiguration::class, mappedBy="trainer")
     */
    private $trainingConfigurations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    public function __construct()
    {
        $this->position = new ArrayCollection();
        $this->player = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->purchasedTrainingSessions = new ArrayCollection();
        $this->trainingSessions = new ArrayCollection();
        $this->trainingConfigurations = new ArrayCollection();
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
        return (string) $this->email;
    }

    public function getFullName():string
    {
        return (string) $this->fullName;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function addRole(string $role):self
    {
        $this->roles[]=$role;
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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDataConsent(): ?bool
    {
        return $this->data_consent;
    }

    public function setDataConsent(bool $data_consent): self
    {
        $this->data_consent = $data_consent;

        return $this;
    }

    public function getMarketingConsent(): ?bool
    {
        return $this->marketing_consent;
    }

    public function setMarketingConsent(bool $marketing_consent): self
    {
        $this->marketing_consent = $marketing_consent;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return Collection<int, Position>
     */
    public function getPosition(): Collection
    {
        return $this->position;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->position->contains($position)) {
            $this->position[] = $position;
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        $this->position->removeElement($position);

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getYearbook(): ?string
    {
        return $this->yearbook;
    }

    public function setYearbook(?string $yearbook): self
    {
        $this->yearbook = $yearbook;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function generateUuid()
    {
        return sprintf( '%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff )
        );
    }

    public function getUserType(): ?int
    {
        return $this->userType;
    }

    public function setUserType(int $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    static function getConstants(): array
    {
        $reflectionClass = new ReflectionClass(User::class);
        return $reflectionClass->getConstants();
    }

    public function getTrainingUnits(): ?string
    {
        return $this->trainingUnits;
    }

    public function setTrainingUnits(string $trainingUnits): self
    {
        $this->trainingUnits = $trainingUnits;

        return $this;
    }

    public function addTrainingUnits(string $trainingUnits):self
    {
        $this->trainingUnits+=$trainingUnits;
        return $this;
    }

    public function removeTrainingUnits(string $trainingUnits):self
    {
        $this->trainingUnits-=$trainingUnits;
        return $this;
    }

    /**
     * @return Collection<int, PlayerManager>
     */
    public function getPlayer(): Collection
    {
        return $this->player;
    }

    public function addPlayer(PlayerManager $player): self
    {
        if (!$this->player->contains($player)) {
            $this->player[] = $player;
            $player->setPlayer($this);
        }

        return $this;
    }

    public function removePlayer(PlayerManager $player): self
    {
        if ($this->player->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getPlayer() === $this) {
                $player->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrainingSession>
     */
    public function getPurchasedTrainingSessions(): Collection
    {
        return $this->purchasedTrainingSessions;
    }

    public function addPurchasedTrainingSession(TrainingSession $purchasedTrainingSession): self
    {
        if (!$this->purchasedTrainingSessions->contains($purchasedTrainingSession)) {
            $this->purchasedTrainingSessions[] = $purchasedTrainingSession;
            $purchasedTrainingSession->setBuyer($this);
        }

        return $this;
    }

    public function removePurchasedTrainingSession(TrainingSession $purchasedTrainingSession): self
    {
        if ($this->purchasedTrainingSessions->removeElement($purchasedTrainingSession)) {
            // set the owning side to null (unless already changed)
            if ($purchasedTrainingSession->getBuyer() === $this) {
                $purchasedTrainingSession->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrainingSession>
     */
    public function getTrainingSessions(): Collection
    {
        return $this->trainingSessions;
    }

    public function addTrainingSession(TrainingSession $trainingSession): self
    {
        if (!$this->trainingSessions->contains($trainingSession)) {
            $this->trainingSessions[] = $trainingSession;
            $trainingSession->setPlayer($this);
        }

        return $this;
    }

    public function removeTrainingSession(TrainingSession $trainingSession): self
    {
        if ($this->trainingSessions->removeElement($trainingSession)) {
            // set the owning side to null (unless already changed)
            if ($trainingSession->getPlayer() === $this) {
                $trainingSession->setPlayer(null);
            }
        }

        return $this;
    }

    public function isManagerExpert():bool
    {
        return in_array("ROLE_MANAGER_EXPERT",$this->roles);
    }

    /**
     * @return Collection<int, TrainingConfiguration>
     */
    public function getTrainingConfigurations(): Collection
    {
        return $this->trainingConfigurations;
    }

    public function addTrainingConfiguration(TrainingConfiguration $trainingConfiguration): self
    {
        if (!$this->trainingConfigurations->contains($trainingConfiguration)) {
            $this->trainingConfigurations[] = $trainingConfiguration;
            $trainingConfiguration->setTrainer($this);
        }

        return $this;
    }

    public function removeTrainingConfiguration(TrainingConfiguration $trainingConfiguration): self
    {
        if ($this->trainingConfigurations->removeElement($trainingConfiguration)) {
            // set the owning side to null (unless already changed)
            if ($trainingConfiguration->getTrainer() === $this) {
                $trainingConfiguration->setTrainer(null);
            }
        }

        return $this;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }
}
