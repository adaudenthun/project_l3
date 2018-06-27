<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParisRepository")
 */
class Paris
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $equipe1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $equipe2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score_equipe1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score_equipe2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    public function getId()
    {
        return $this->id;
    }

    public function getEquipe1(): ?string
    {
        return $this->equipe1;
    }

    public function setEquipe1(?string $equipe1): self
    {
        $this->equipe1 = $equipe1;

        return $this;
    }

    public function getEquipe2(): ?string
    {
        return $this->equipe2;
    }

    public function setEquipe2(?string $equipe2): self
    {
        $this->equipe2 = $equipe2;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getScoreEquipe1(): ?int
    {
        return $this->score_equipe1;
    }

    public function setScoreEquipe1(?int $score_equipe1): self
    {
        $this->score_equipe1 = $score_equipe1;

        return $this;
    }

    public function getScoreEquipe2(): ?int
    {
        return $this->score_equipe2;
    }

    public function setScoreEquipe2(?int $score_equipe2): self
    {
        $this->score_equipe2 = $score_equipe2;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
