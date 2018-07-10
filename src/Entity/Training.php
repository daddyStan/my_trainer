<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingRepository")
 */
class Training
{
    private $training_id;
    private $training_name;
    private $description;
    private $creation_date;
    private $last_update_date;
    private $exercises;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
    }

    public function getTrainingId(): ?int
    {
        return $this->training_id;
    }

    public function getTrainingName(): ?string
    {
        return $this->training_name;
    }

    public function setTrainingName(string $training_name): self
    {
        $this->training_name = $training_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->last_update_date;
    }

    public function setLastUpdateDate(\DateTimeInterface $last_update_date): self
    {
        $this->last_update_date = $last_update_date;

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->setTrainingId($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->contains($exercise)) {
            $this->exercises->removeElement($exercise);
            // set the owning side to null (unless already changed)
            if ($exercise->getTrainingId() === $this) {
                $exercise->setTrainingId(null);
            }
        }

        return $this;
    }

 

}
