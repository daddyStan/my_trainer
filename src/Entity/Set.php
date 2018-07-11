<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Set
{
    private $set_id;
    private $comment;
    private $weight;
    private $tries;
    private $creation_date;
    private $last_update_date;
    private $exercise_id;

    public function getSetId(): ?int
    {
        return $this->set_id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTries(): ?int
    {
        return $this->tries;
    }

    public function setTries(int $tries): self
    {
        $this->tries = $tries;

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

    public function getExerciseId(): ?Exercise
    {
        return $this->exercise_id;
    }

    public function setExerciseId(?Exercise $exercise_id): self
    {
        $this->exercise_id = $exercise_id;

        return $this;
    }

}
