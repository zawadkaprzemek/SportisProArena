<?php

namespace App\Entity;

use App\Repository\TrainingUnitThrowConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrainingUnitThrowConfigRepository::class)
 */
class TrainingUnitThrowConfig
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $launcher;

    /**
     * @ORM\Column(type="integer")
     */
    private $power;

    /**
     * @ORM\Column(type="integer")
     */
    private $angle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sound;

    /**
     * @ORM\Column(type="boolean")
     */
    private $light;

    /**
     * @ORM\Column(type="array")
     */
    private $startPlace = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @ORM\ManyToOne(targetEntity=TrainingSeries::class, inversedBy="trainingUnitThrowConfigs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trainingSeries;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLauncher(): ?int
    {
        return $this->launcher;
    }

    public function setLauncher(int $launcher): self
    {
        $this->launcher = $launcher;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getAngle(): ?int
    {
        return $this->angle;
    }

    public function setAngle(int $angle): self
    {
        $this->angle = $angle;

        return $this;
    }

    public function getSound(): ?bool
    {
        return $this->sound;
    }

    public function setSound(bool $sound): self
    {
        $this->sound = $sound;

        return $this;
    }

    public function getLight(): ?bool
    {
        return $this->light;
    }

    public function setLight(bool $light): self
    {
        $this->light = $light;

        return $this;
    }

    public function getStartPlace(): ?array
    {
        return $this->startPlace;
    }

    public function setStartPlace(array $startPlace): self
    {
        $this->startPlace = $startPlace;

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

    public function getTrainingSeries(): ?TrainingSeries
    {
        return $this->trainingSeries;
    }

    public function setTrainingSeries(?TrainingSeries $trainingSeries): self
    {
        $this->trainingSeries = $trainingSeries;

        return $this;
    }
}
