<?php

namespace App\Entity;

use App\Repository\ConfrontationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfrontationRepository::class)
 */
class Confrontation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tourney::class, inversedBy="confrontations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tourney;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $first_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $final_date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $red_side;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $blue_side;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_first_picker;

    /**
     * @ORM\ManyToOne(targetEntity=Step::class, inversedBy="confrontations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $step;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=TourneyStaff::class, inversedBy="confrontations")
     */
    private $ref;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="confrontation")
     */
    private $pools;

    public function __construct()
    {
        $this->pools = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstDate(): ?\DateTimeInterface
    {
        return $this->first_date;
    }

    public function setFirstDate(?\DateTimeInterface $first_date): self
    {
        $this->first_date = $first_date;

        return $this;
    }

    public function getFinalDate(): ?\DateTimeInterface
    {
        return $this->final_date;
    }

    public function setFinalDate(\DateTimeInterface $final_date): self
    {
        $this->final_date = $final_date;

        return $this;
    }

    public function getRedSide(): ?int
    {
        return $this->red_side;
    }

    public function setRedSide(?int $red_side): self
    {
        $this->red_side = $red_side;

        return $this;
    }

    public function getBlueSide(): ?int
    {
        return $this->blue_side;
    }

    public function setBlueSide(?int $blue_side): self
    {
        $this->blue_side = $blue_side;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsFirstPicker(): ?bool
    {
        return $this->is_first_picker;
    }

    public function setIsFirstPicker(bool $is_first_picker): self
    {
        $this->is_first_picker = $is_first_picker;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getRef(): ?TourneyStaff
    {
        return $this->ref;
    }

    public function setRef(?TourneyStaff $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getPools(): Collection
    {
        return $this->pools;
    }

    public function addPool(Group $pool): self
    {
        if (!$this->pools->contains($pool)) {
            $this->pools[] = $pool;
            $pool->addConfrontation($this);
        }

        return $this;
    }

    public function removePool(Group $pool): self
    {
        if ($this->pools->removeElement($pool)) {
            $pool->removeConfrontation($this);
        }

        return $this;
    }
}
