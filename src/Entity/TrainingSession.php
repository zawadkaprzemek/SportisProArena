<?php

namespace App\Entity;

use App\Repository\TrainingSessionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TrainingSessionRepository::class)
 * @UniqueEntity(fields={"uuid"}, message="Uuid must be unique")
 */
class TrainingSession
{
    const TRAINING_STATUSES=[
        '-1'=>'canceled',
        '0'=>'to-do',
        '1'=>'done'
    ];


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchasedTrainingSessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * @ORM\ManyToOne(targetEntity=Arena::class, inversedBy="trainingSessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $arena;

    /**
     * @ORM\Column(type="integer")
     * 0 - oczekująca na odbycie
     * 1 - odbyta
     * -1 - przeterminowana
     */
    private $status=0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sessionDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trainingSessions")
     */
    private $player;

    /**
     * @ORM\Column(type="string", length=12, unique=true)
     */
    private $uuid;

    public function __construct()
    {
        $this->uuid=$this->generateUuid();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getArena(): ?Arena
    {
        return $this->arena;
    }

    public function setArena(?Arena $arena): self
    {
        $this->arena = $arena;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSessionDate(): ?\DateTimeInterface
    {
        return $this->sessionDate;
    }

    public function setSessionDate(\DateTimeInterface $sessionDate): self
    {
        $this->sessionDate = $sessionDate;

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): self
    {
        $this->player = $player;

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

    public function generateUuid()
    {
        return sprintf( '%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff )
        );
    }
}
