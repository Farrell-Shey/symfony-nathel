<?php

namespace App\Entity;

use App\Repository\LobbieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LobbieRepository::class)
 */
class Lobbie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_replay;

    /**
     * @ORM\ManyToOne(targetEntity=Tourney::class, inversedBy="lobbies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tourney;

    /**
     * @ORM\ManyToOne(targetEntity=Step::class, inversedBy="lobbies")
     */
    private $step;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIsReplay(): ?bool
    {
        return $this->is_replay;
    }

    public function setIsReplay(bool $is_replay): self
    {
        $this->is_replay = $is_replay;

        return $this;
    }

    public function getTourney(): ?Tourney
    {
        return $this->tourney;
    }

    public function setTourney(?Tourney $tourney): self
    {
        $this->tourney = $tourney;

        return $this;
    }

    public function getStep(): ?Step
    {
        return $this->step;
    }

    public function setStep(?Step $step): self
    {
        $this->step = $step;

        return $this;
    }
}
