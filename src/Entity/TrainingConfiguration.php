<?php

namespace App\Entity;

use App\Repository\TrainingConfigurationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TrainingConfigurationRepository::class)
 */
class TrainingConfiguration
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trainingConfigurations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trainer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * 0 - in progres
     * 1 - finished
     */
    private $status=0;

    /**
     * @ORM\OneToMany(targetEntity=TrainingUnit::class, mappedBy="configuration")
     */
    private $trainingUnits;

    public function __construct()
    {
        $this->trainingUnits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrainer(): ?User
    {
        return $this->trainer;
    }

    public function setTrainer(?User $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, TrainingUnit>
     */
    public function getTrainingUnits(): Collection
    {
        return $this->trainingUnits;
    }

    public function addTrainingUnit(TrainingUnit $trainingUnit): self
    {
        if (!$this->trainingUnits->contains($trainingUnit)) {
            $this->trainingUnits[] = $trainingUnit;
            $trainingUnit->setConfiguration($this);
        }

        return $this;
    }

    public function removeTrainingUnit(TrainingUnit $trainingUnit): self
    {
        if ($this->trainingUnits->removeElement($trainingUnit)) {
            // set the owning side to null (unless already changed)
            if ($trainingUnit->getConfiguration() === $this) {
                $trainingUnit->setConfiguration(null);
            }
        }

        return $this;
    }
}
