<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Day
{
    private $day_id;
    private $training_id;
    private $exercise_id;
    private $set_id;
    private $creation_date;
    private $finish_date;
    private $main_time;
    private $user_id;
    private $deleted;

    public function getDayId(): ?int
    {
        return $this->day_id;
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

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finish_date;
    }

    public function setFinishDate(?\DateTimeInterface $finish_date): self
    {
        $this->finish_date = $finish_date;

        return $this;
    }

    public function getMainTime(): ?string
    {
        return $this->main_time;
    }

    public function setMainTime(?string $main_time): self
    {
        $this->main_time = $main_time;

        return $this;
    }

    public function getTrainingId(): ?Training
    {
        return $this->training_id;
    }

    public function setTrainingId(?Training $training_id): self
    {
        $this->training_id = $training_id;

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

    public function getSetId(): ?Set
    {
        return $this->set_id;
    }

    public function setSetId(?Set $set_id): self
    {
        $this->set_id = $set_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
