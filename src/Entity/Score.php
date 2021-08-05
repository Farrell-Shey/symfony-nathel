<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScoreRepository::class)
 */
class Score
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $note;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $acc;

    /**
     * @ORM\Column(type="integer")
     */
    private $combo;

    /**
     * @ORM\Column(type="integer")
     */
    private $perfect;

    /**
     * @ORM\Column(type="integer")
     */
    private $good;

    /**
     * @ORM\Column(type="integer")
     */
    private $bad;

    /**
     * @ORM\Column(type="integer")
     */
    private $miss;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAcc(): ?string
    {
        return $this->acc;
    }

    public function setAcc(string $acc): self
    {
        $this->acc = $acc;

        return $this;
    }

    public function getCombo(): ?int
    {
        return $this->combo;
    }

    public function setCombo(int $combo): self
    {
        $this->combo = $combo;

        return $this;
    }

    public function getPerfect(): ?int
    {
        return $this->perfect;
    }

    public function setPerfect(int $perfect): self
    {
        $this->perfect = $perfect;

        return $this;
    }

    public function getGood(): ?int
    {
        return $this->good;
    }

    public function setGood(int $good): self
    {
        $this->good = $good;

        return $this;
    }

    public function getBad(): ?int
    {
        return $this->bad;
    }

    public function setBad(int $bad): self
    {
        $this->bad = $bad;

        return $this;
    }

    public function getMiss(): ?int
    {
        return $this->miss;
    }

    public function setMiss(int $miss): self
    {
        $this->miss = $miss;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
