<?php

namespace App\Entity;

use App\Repository\TrainingSeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingSeriesRepository", repositoryClass=TrainingSeriesRepository::class)
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

    private $mainScreen;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $targetType;

    /**
     * @ORM\Column(type="array", nullable=true)
     * [
     * 'targetsMap','targetsShield','targetsMapPoints','targetsShieldPoints','targetPresentation','disablePointsBehindGoal'
     * ]
     */
    private $targetConfiguration = [];

    /** @var string example 0,0;120,-20 */
    private $targets = "";

    private $targetsMap;
    private $targetShield;

    /** @var string */
    private $targetsMapPoints;

    /** @var string */
    private $targetsShieldPoints;

    /** @var string */
    private $targetPresentation;

    /** @var bool */
    private $disablePointsBehindGoal = false;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $playerTasks = [];

    private $playerTasksWhat;

    private $playerTasksHow;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $seriesVolume = 1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $soundVolume;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $timeConfiguration = [];

    private $timeConfigurationMeaning;

    private $timeConfigurationMin;

    private $timeConfigurationMax;

    private $timeConfigurationPercent;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $breaksConfiguration = [];

    private $seriesBreaks = 0;

    private $throwBreaks = 0;

    private $unitBreaks = 0;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $trainingObjectives = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $sort = 1;

    /**
     * @ORM\OneToMany(targetEntity=TrainingUnitThrowConfig::class, mappedBy="trainingSeries",cascade={"persist","remove"})
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
        //return $this->seriesVolume;
        return sizeof($this->trainingUnitThrowConfigs);
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

    public function getBreakValue(string $type): int
    {
        if (array_key_exists($type, $this->breaksConfiguration)) {
            return $this->breaksConfiguration[$type] ?? 0;
        } else {
            return 0;
        }
    }

    /**
     * @return mixed
     */
    public function getMainScreen()
    {
        return $this->screensConfiguration['mainScreen'] ?? null;
    }

    /**
     * @param mixed $mainScreen
     */
    public function setMainScreen($mainScreen): void
    {
        $this->screensConfiguration['mainScreen'] = $mainScreen;
    }

    /**
     * @return mixed
     */
    public function getPlayerTasksWhat()
    {
        return $this->playerTasks['what'] ?? null;
    }

    /**
     * @param mixed $playerTasksWhat
     */
    public function setPlayerTasksWhat($playerTasksWhat): void
    {
        $this->playerTasks['what'] = $playerTasksWhat;
    }

    /**
     * @return mixed
     */
    public function getPlayerTasksHow()
    {
        return $this->playerTasks['how'] ?? null;
    }

    /**
     * @param mixed $playerTasksHow
     */
    public function setPlayerTasksHow($playerTasksHow): void
    {
        $this->playerTasks['how'] = $playerTasksHow;
    }

    /**
     * @return mixed
     */
    public function getTimeConfigurationMeaning()
    {
        return $this->timeConfiguration['meaning'] ?? null;
    }

    /**
     * @param mixed $timeConfigurationMeaning
     */
    public function setTimeConfigurationMeaning($timeConfigurationMeaning): void
    {
        $this->timeConfiguration['meaning'] = $timeConfigurationMeaning;
    }

    /**
     * @return mixed
     */
    public function getTimeConfigurationMin()
    {
        return $this->timeConfiguration['min'] ?? null;
    }

    /**
     * @param mixed $timeConfigurationMin
     */
    public function setTimeConfigurationMin($timeConfigurationMin): void
    {
        $this->timeConfiguration['min'] = $timeConfigurationMin;
    }

    /**
     * @return mixed
     */
    public function getTimeConfigurationMax()
    {
        return $this->timeConfiguration['max'] ?? null;
    }

    /**
     * @param mixed $timeConfigurationMax
     */
    public function setTimeConfigurationMax($timeConfigurationMax): void
    {
        $this->timeConfiguration['max'] = $timeConfigurationMax;
    }

    /**
     * @return mixed
     */
    public function getTimeConfigurationPercent()
    {
        return $this->timeConfiguration['percent'] ?? null;
    }

    /**
     * @param mixed $timeConfigurationPercent
     */
    public function setTimeConfigurationPercent($timeConfigurationPercent): void
    {
        $this->timeConfiguration['percent'] = $timeConfigurationPercent;
    }

    /**
     * @return mixed
     */
    public function getSeriesBreaks()
    {
        return $this->breaksConfiguration['series'] ?? 0;
    }

    /**
     * @param mixed $seriesBreaks
     */
    public function setSeriesBreaks($seriesBreaks): void
    {
        $this->breaksConfiguration['series'] = $seriesBreaks;
    }

    /**
     * @return mixed
     */
    public function getThrowBreaks()
    {
        return $this->breaksConfiguration['throws'] ?? 0;
    }

    /**
     * @param mixed $throwBreaks
     */
    public function setThrowBreaks($throwBreaks): void
    {
        $this->breaksConfiguration['throws'] = $throwBreaks;
    }

    /**
     * @return mixed
     */
    public function getUnitBreaks()
    {
        return $this->breaksConfiguration['unit'] ?? 0;
    }

    /**
     * @param mixed $unitBreaks
     */
    public function setUnitBreaks($unitBreaks): void
    {
        $this->breaksConfiguration['unit'] = $unitBreaks;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return $this->targetConfiguration['targets'] ?? "";
    }

    /**
     * @param string $targets
     */
    public function setTargets(string $targets): void
    {
        $this->targetConfiguration['targets'] = $targets;
    }

    /**
     * @return string
     */
    public function getTargetsMap(): string
    {
        return $this->targetConfiguration['targetsMap'] ?? "";
    }

    /**
     * @param ?string $targetsMap
     */
    public function setTargetsMap(?string $targetsMap): void
    {
        $this->targetConfiguration['targetsMap'] = $targetsMap;
    }

    /**
     * @return string
     */
    public function getTargetsShield(): string
    {
        return $this->targetConfiguration['targetsShield'] ?? "";
    }

    /**
     * @param ?string $targetsShield
     */
    public function setTargetsShield(?string $targetsShield): void
    {
        $this->targetConfiguration['targetsShield'] = $targetsShield;
    }

    /**
     * @return ?string
     */
    public function getTargetsMapPoints(): ?string
    {
        return $this->targetConfiguration['targetsMapPoints'] ?? "";
    }

    /**
     * @param string|null $points
     */
    public function setTargetsMapPoints(?string $points): void
    {
        $this->targetConfiguration['targetsMapPoints'] = $points;
    }

    /**
     * @return string
     */
    public function getTargetsShieldPoints(): ?string
    {
        return $this->targetConfiguration['targetsShieldPoints'] ?? "";
    }

    /**
     * @param string|null $points
     */
    public function setTargetsShieldPoints(?string $points): void
    {
        $this->targetConfiguration['targetsShieldPoints'] = $points;
    }

    /**
     * @return string
     */
    public function getTargetPresentation(): ?string
    {
        return $this->targetConfiguration['targetPresentation'] ?? null;
    }

    /**
     * @param string|null $targetPresentation
     */
    public function setTargetPresentation(?string $targetPresentation): void
    {
        $this->targetConfiguration['targetPresentation'] = $targetPresentation;
    }

    /**
     * @return bool
     */
    public function isDisablePointsBehindGoal(): bool
    {
        return $this->targetConfiguration['disablePointsBehindGoal'] ?? false;
    }

    /**
     * @param bool $disablePointsBehindGoal
     */
    public function setDisablePointsBehindGoal(?bool $disablePointsBehindGoal): void
    {
        $this->targetConfiguration['disablePointsBehindGoal'] = $disablePointsBehindGoal;
    }
}
