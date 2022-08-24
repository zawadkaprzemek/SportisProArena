<?php

namespace App\Entity;

use App\Repository\TrainingUnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TrainingUnitRepository::class)
 */
class TrainingUnit
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ageCategory;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $trainingType;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $test;

    /**
     * @ORM\Column(type="integer")
     */
    private $trainingGroup;

    /**
     * @ORM\Column(type="array")
     */
    private $trainingSubGroupsAgeCategories = [];

    /**
     * @ORM\Column(type="array")
     */
    private $trainingSubGroupsLevels = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $seriesCount;

    /**
     * @ORM\OneToMany(targetEntity=TrainingSeries::class, mappedBy="trainingUnit",cascade={"persist","remove"})
     */
    private $trainingSeries;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trainingConfigurations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trainer;

    /**
     * @ORM\Column(type="integer")
     */
    private $step=1;

    /**
     * @ORM\Column(type="integer")
     * 0 - rozpoczęty
     * 1 - zakończony
     */
    private $status=0;

    public function __construct()
    {
        $this->trainingSeries = new ArrayCollection();
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

    public function getAgeCategory(): ?string
    {
        return $this->ageCategory;
    }

    public function setAgeCategory(string $ageCategory): self
    {
        $this->ageCategory = $ageCategory;

        return $this;
    }

    public function getTrainingType(): ?string
    {
        return $this->trainingType;
    }

    public function setTrainingType(string $trainingType): self
    {
        $this->trainingType = $trainingType;

        return $this;
    }

    public function getTest(): ?string
    {
        return $this->test;
    }

    public function setTest(string $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getTrainingGroup(): ?int
    {
        return $this->trainingGroup;
    }

    public function setTrainingGroup(int $trainingGroup): self
    {
        $this->trainingGroup = $trainingGroup;

        return $this;
    }

    public function getTrainingSubGroupsAgeCategories(): ?array
    {
        return $this->trainingSubGroupsAgeCategories;
    }

    public function setTrainingSubGroupsAgeCategories(array $trainingSubGroupsAgeCategories): self
    {
        $this->trainingSubGroupsAgeCategories = $trainingSubGroupsAgeCategories;

        return $this;
    }

    public function getTrainingSubGroupsLevels(): ?array
    {
        return $this->trainingSubGroupsLevels;
    }

    public function setTrainingSubGroupsLevels(array $trainingSubGroupsLevels): self
    {
        $this->trainingSubGroupsLevels = $trainingSubGroupsLevels;

        return $this;
    }

    public function getSeriesCount(): ?int
    {
        return $this->seriesCount;
    }

    public function setSeriesCount(?int $seriesCount): self
    {
        $this->seriesCount = $seriesCount;

        return $this;
    }

    /**
     * @return Collection<int, TrainingSeries>
     */
    public function getTrainingSeries(): Collection
    {
        return $this->trainingSeries;
    }

    public function addTrainingSeries(TrainingSeries $trainingSeries): self
    {
        if (!$this->trainingSeries->contains($trainingSeries)) {
            $this->trainingSeries[] = $trainingSeries;
            $trainingSeries->setTrainingUnit($this);
        }

        return $this;
    }

    public function removeTrainingSeries(TrainingSeries $trainingSeries): self
    {
        if ($this->trainingSeries->removeElement($trainingSeries)) {
            // set the owning side to null (unless already changed)
            if ($trainingSeries->getTrainingUnit() === $this) {
                $trainingSeries->setTrainingUnit(null);
            }
        }

        return $this;
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

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

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

    public function nextStep()
    {
        $this->step++;
    }
}
