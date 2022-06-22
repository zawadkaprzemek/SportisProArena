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
     * @ORM\ManyToOne(targetEntity=TrainingConfiguration::class, inversedBy="trainingUnits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $configuration;

    /**
     * @ORM\Column(type="integer")
     */
    private $ageCategory;

    /**
     * @ORM\Column(type="integer")
     */
    private $trainingType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $test;

    /**
     * @ORM\Column(type="integer")
     */
    private $trainingGroup;

    /**
     * @ORM\Column(type="array")
     * ['groups'=>array,'levels'=>array]
     */
    private $trainingSubGroups = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $seriesCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $screensCount;

    /**
     * @ORM\Column(type="array")
     */
    private $screensConfiguration = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $targetType;

    /**
     * @ORM\Column(type="array")
     */
    private $targetConfiguration = [];

    /**
     * @ORM\Column(type="array")
     */
    private $playerTasks = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $throwsCount;

    /**
     * @ORM\Column(type="array")
     */
    private $throwsConfiguration = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $sound;

    /**
     * @ORM\Column(type="integer")
     */
    private $soundVolume;

    /**
     * @ORM\Column(type="array")
     */
    private $timeConfiguration = [];

    /**
     * @ORM\Column(type="array")
     */
    private $breaksConfiguration = [];

    /**
     * @ORM\ManyToMany(targetEntity=TrainingObjectives::class, inversedBy="trainingUnits")
     */
    private $mainObjectives;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    public function __construct()
    {
        $this->mainObjectives = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfiguration(): ?TrainingConfiguration
    {
        return $this->configuration;
    }

    public function setConfiguration(?TrainingConfiguration $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function getAgeCategory(): ?int
    {
        return $this->ageCategory;
    }

    public function setAgeCategory(int $ageCategory): self
    {
        $this->ageCategory = $ageCategory;

        return $this;
    }

    public function getTrainingType(): ?int
    {
        return $this->trainingType;
    }

    public function setTrainingType(int $trainingType): self
    {
        $this->trainingType = $trainingType;

        return $this;
    }

    public function isTest(): ?int
    {
        return $this->test;
    }

    public function setTest(bool $test): self
    {
        $this->teste = $test;

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

    public function getTrainingSubGroups(): ?array
    {
        return $this->trainingSubGroups;
    }

    public function setTrainingSubGroups(array $trainingSubGroups): self
    {
        $this->trainingSubGroups = $trainingSubGroups;

        return $this;
    }

    public function getSeriesCount(): ?int
    {
        return $this->seriesCount;
    }

    public function setSeriesCount(int $seriesCount): self
    {
        $this->seriesCount = $seriesCount;

        return $this;
    }

    public function getScreensCount(): ?int
    {
        return $this->screensCount;
    }

    public function setScreensCount(int $screensCount): self
    {
        $this->screensCount = $screensCount;

        return $this;
    }

    public function getScreensConfiguration(): ?array
    {
        return $this->screensConfiguration;
    }

    public function setScreensConfiguration(array $screensConfiguration): self
    {
        $this->screensConfiguration = $screensConfiguration;

        return $this;
    }

    public function getTargetType(): ?int
    {
        return $this->targetType;
    }

    public function setTargetType(int $targetType): self
    {
        $this->targetType = $targetType;

        return $this;
    }

    public function getTargetConfiguration(): ?array
    {
        return $this->targetConfiguration;
    }

    public function setTargetConfiguration(array $targetConfiguration): self
    {
        $this->targetConfiguration = $targetConfiguration;

        return $this;
    }

    public function getPlayerTasks(): ?array
    {
        return $this->playerTasks;
    }

    public function setPlayerTasks(array $playerTasks): self
    {
        $this->playerTasks = $playerTasks;

        return $this;
    }

    public function getThrowsCount(): ?int
    {
        return $this->throwsCount;
    }

    public function setThrowsCount(int $throwsCount): self
    {
        $this->throwsCount = $throwsCount;

        return $this;
    }

    public function getThrowsConfiguration(): ?array
    {
        return $this->throwsConfiguration;
    }

    public function setThrowsConfiguration(array $throwsConfiguration): self
    {
        $this->throwsConfiguration = $throwsConfiguration;

        return $this;
    }

    public function getSound(): ?int
    {
        return $this->sound;
    }

    public function setSound(int $sound): self
    {
        $this->sound = $sound;

        return $this;
    }

    public function getSoundVolume(): ?int
    {
        return $this->soundVolume;
    }

    public function setSoundVolume(int $soundVolume): self
    {
        $this->soundVolume = $soundVolume;

        return $this;
    }

    public function getTimeConfiguration(): ?array
    {
        return $this->timeConfiguration;
    }

    public function setTimeConfiguration(array $timeConfiguration): self
    {
        $this->timeConfiguration = $timeConfiguration;

        return $this;
    }

    public function getBreaksConfiguration(): ?array
    {
        return $this->breaksConfiguration;
    }

    public function setBreaksConfiguration(array $breaksConfiguration): self
    {
        $this->breaksConfiguration = $breaksConfiguration;

        return $this;
    }

    /**
     * @return Collection<int, TrainingObjectives>
     */
    public function getMainObjectives(): Collection
    {
        return $this->mainObjectives;
    }

    public function addMainObjective(TrainingObjectives $mainObjective): self
    {
        if (!$this->mainObjectives->contains($mainObjective)) {
            $this->mainObjectives[] = $mainObjective;
        }

        return $this;
    }

    public function removeMainObjective(TrainingObjectives $mainObjective): self
    {
        $this->mainObjectives->removeElement($mainObjective);

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

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
