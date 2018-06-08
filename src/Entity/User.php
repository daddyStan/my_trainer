<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_login_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_registration_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $user_last_visit_date;

    public function getId()
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(?string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->user_password;
    }

    public function setUserPassword(string $user_password): self
    {
        $this->user_password = $user_password;

        return $this;
    }

    public function getUserLoginName(): ?string
    {
        return $this->user_login_name;
    }

    public function setUserLoginName(string $user_login_name): self
    {
        $this->user_login_name = $user_login_name;

        return $this;
    }

    public function getUserRegistrationDate(): ?string
    {
        return $this->user_registration_date;
    }

    public function setUserRegistrationDate(string $user_registration_date): self
    {
        $this->user_registration_date = $user_registration_date;

        return $this;
    }

    public function getUserLastVisitDate(): ?\DateTimeInterface
    {
        return $this->user_last_visit_date;
    }

    public function setUserLastVisitDate(?\DateTimeInterface $user_last_visit_date): self
    {
        $this->user_last_visit_date = $user_last_visit_date;

        return $this;
    }
}
