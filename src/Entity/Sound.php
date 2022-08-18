<?php

namespace App\Entity;

use App\Repository\SoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SoundRepository::class)
 */
class Sound
{
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
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\OneToMany(targetEntity=TrainingSeries::class, mappedBy="surroundingSound")
     */
    private $trainingSeries;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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
            $trainingSeries->setSurroundingSound($this);
        }

        return $this;
    }

    public function removeTrainingSeries(TrainingSeries $trainingSeries): self
    {
        if ($this->trainingSeries->removeElement($trainingSeries)) {
            // set the owning side to null (unless already changed)
            if ($trainingSeries->getSurroundingSound() === $this) {
                $trainingSeries->setSurroundingSound(null);
            }
        }

        return $this;
    }
}
