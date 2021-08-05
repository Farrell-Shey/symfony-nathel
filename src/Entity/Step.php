<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StepRepository::class)
 */
class Step
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
    private $step;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $best_of;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bans;

    /**
     * @ORM\OneToMany(targetEntity=Confrontation::class, mappedBy="step", orphanRemoval=true)
     */
    private $confrontations;

    /**
     * @ORM\OneToMany(targetEntity=Lobbie::class, mappedBy="step")
     */
    private $lobbies;

    /**
     * @ORM\ManyToOne(targetEntity=Tourney::class, inversedBy="steps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tourney;

    /**
     * @ORM\ManyToOne(targetEntity=Mappool::class, inversedBy="steps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mappool;

    public function __construct()
    {
        $this->confrontations = new ArrayCollection();
        $this->lobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStep(): ?string
    {
        return $this->step;
    }

    public function setStep(string $step): self
    {
        $this->step = $step;

        return $this;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getBestOf(): ?int
    {
        return $this->best_of;
    }

    public function setBestOf(?int $best_of): self
    {
        $this->best_of = $best_of;

        return $this;
    }

    public function getBans(): ?int
    {
        return $this->bans;
    }

    public function setBans(?int $bans): self
    {
        $this->bans = $bans;

        return $this;
    }

    /**
     * @return Collection|Confrontation[]
     */
    public function getConfrontations(): Collection
    {
        return $this->confrontations;
    }

    public function addConfrontation(Confrontation $confrontation): self
    {
        if (!$this->confrontations->contains($confrontation)) {
            $this->confrontations[] = $confrontation;
            $confrontation->setStep($this);
        }

        return $this;
    }

    public function removeConfrontation(Confrontation $confrontation): self
    {
        if ($this->confrontations->removeElement($confrontation)) {
            // set the owning side to null (unless already changed)
            if ($confrontation->getStep() === $this) {
                $confrontation->setStep(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Lobbie[]
     */
    public function getLobbies(): Collection
    {
        return $this->lobbies;
    }

    public function addLobby(Lobbie $lobby): self
    {
        if (!$this->lobbies->contains($lobby)) {
            $this->lobbies[] = $lobby;
            $lobby->setStep($this);
        }

        return $this;
    }

    public function removeLobby(Lobbie $lobby): self
    {
        if ($this->lobbies->removeElement($lobby)) {
            // set the owning side to null (unless already changed)
            if ($lobby->getStep() === $this) {
                $lobby->setStep(null);
            }
        }

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

    public function getMappool(): ?Mappool
    {
        return $this->mappool;
    }

    public function setMappool(?Mappool $mappool): self
    {
        $this->mappool = $mappool;

        return $this;
    }
}
