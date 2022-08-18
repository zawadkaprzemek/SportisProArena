<?php

namespace App\Entity;

use App\Repository\TrainingSeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TrainingSeriesRepository::class)
 */
class TrainingSeries
{

    use TimestampableEntity;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrainingUnit::class, inversedBy="trainingSeries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trainingUnit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $screensCount;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $screensConfiguration = [];

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $targetType;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $targetConfiguration = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $playerTasks = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $seriesVolume;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $soundVolume;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $timeConfiguration = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $breaksConfiguration = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $trainingObjectives = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @ORM\OneToMany(targetEntity=TrainingUnitThrowConfig::class, mappedBy="trainingSeries")
     */
    private $trainingUnitThrowConfigs;

    /**
     * @ORM\ManyToOne(targetEntity=Sound::class, inversedBy="trainingSeries")
     * @ORM\JoinColumn(nullable=true)
     */
    private $surroundingSound;

    public function __construct()
    {
        $this->trainingUnitThrowConfigs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrainingUnit(): ?TrainingUnit
    {
        return $this->trainingUnit;
    }

    public function setTrainingUnit(?TrainingUnit $trainingUnit): self
    {
        $this->trainingUnit = $trainingUnit;

        return $this;
    }

    public function getScreensCount(): ?int
    {
        return $this->screensCount;
    }

    public function setScreensCount(?int $screensCount): self
    {
        $this->screensCount = $screensCount;

        return $this;
    }

    public function getScreensConfiguration(): ?array
    {
        return $this->screensConfiguration;
    }

    public function setScreensConfiguration(?array $screensConfiguration): self
    {
        $this->screensConfiguration = $screensConfiguration;

        return $this;
    }

    public function getTargetType(): ?string
    {
        return $this->targetType;
    }

    public function setTargetType(?string $targetType): self
    {
        $this->targetType = $targetType;

        return $this;
    }

    public function getTargetConfiguration(): ?array
    {
        return $this->targetConfiguration;
    }

    public function setTargetConfiguration(?array $targetConfiguration): self
    {
        $this->targetConfiguration = $targetConfiguration;

        return $this;
    }

    public function getPlayerTasks(): ?array
    {
        return $this->playerTasks;
    }

    public function setPlayerTasks(?array $playerTasks): self
    {
        $this->playerTasks = $playerTasks;

        return $this;
    }

    public function getSeriesVolume(): ?int
    {
        return $this->seriesVolume;
    }

    public function setSeriesVolume(?int $seriesVolume): self
    {
        $this->seriesVolume = $seriesVolume;

        return $this;
    }

    public function getSoundVolume(): ?int
    {
        return $this->soundVolume;
    }

    public function setSoundVolume(?int $soundVolume): self
    {
        $this->soundVolume = $soundVolume;

        return $this;
    }

    public function getTimeConfiguration(): ?array
    {
        return $this->timeConfiguration;
    }

    public function setTimeConfiguration(?array $timeConfiguration): self
    {
        $this->timeConfiguration = $timeConfiguration;

        return $this;
    }

    public function getBreaksConfiguration(): ?array
    {
        return $this->breaksConfiguration;
    }

    public function setBreaksConfiguration(?array $breaksConfiguration): self
    {
        $this->breaksConfiguration = $breaksConfiguration;

        return $this;
    }

    public function getTrainingObjectives(): ?array
    {
        return $this->trainingObjectives;
    }

    public function setTrainingObjectives(?array $trainingObjectives): self
    {
        $this->trainingObjectives = $trainingObjectives;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return Collection<int, TrainingUnitThrowConfig>
     */
    public function getTrainingUnitThrowConfigs(): Collection
    {
        return $this->trainingUnitThrowConfigs;
    }

    public function addTrainingUnitThrowConfig(TrainingUnitThrowConfig $trainingUnitThrowConfig): self
    {
        if (!$this->trainingUnitThrowConfigs->contains($trainingUnitThrowConfig)) {
            $this->trainingUnitThrowConfigs[] = $trainingUnitThrowConfig;
            $trainingUnitThrowConfig->setTrainingSeries($this);
        }

        return $this;
    }

    public function removeTrainingUnitThrowConfig(TrainingUnitThrowConfig $trainingUnitThrowConfig): self
    {
        if ($this->trainingUnitThrowConfigs->removeElement($trainingUnitThrowConfig)) {
            // set the owning side to null (unless already changed)
            if ($trainingUnitThrowConfig->getTrainingSeries() === $this) {
                $trainingUnitThrowConfig->setTrainingSeries(null);
            }
        }

        return $this;
    }

    public function getSurroundingSound(): ?Sound
    {
        return $this->surroundingSound;
    }

    public function setSurroundingSound(?Sound $surroundingSound): self
    {
        $this->surroundingSound = $surroundingSound;

        return $this;
    }

    public function getBreakValue(string $type):int
    {
        if(array_key_exists($type,$this->breaksConfiguration))
        {
            return $this->breaksConfiguration[$type];
        }else{
            return 0;
        }
    }
}
