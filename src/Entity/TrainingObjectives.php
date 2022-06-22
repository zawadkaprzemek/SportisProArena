<?php

namespace App\Entity;

use App\Repository\TrainingObjectivesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TrainingObjectivesRepository::class)
 */
class TrainingObjectives
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
     * @ORM\ManyToMany(targetEntity=TrainingUnit::class, mappedBy="mainObjectives")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $trainingUnit->addMainObjective($this);
        }

        return $this;
    }

    public function removeTrainingUnit(TrainingUnit $trainingUnit): self
    {
        if ($this->trainingUnits->removeElement($trainingUnit)) {
            $trainingUnit->removeMainObjective($this);
        }

        return $this;
    }
}
