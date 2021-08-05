<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @ORM\ManyToOne(targetEntity=Tourney::class, inversedBy="pools")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tourney;

    /**
     * @ORM\ManyToMany(targetEntity=Confrontation::class, inversedBy="pools")
     */
    private $confrontation;

    /**
     * @ORM\OneToMany(targetEntity=GroupPlayer::class, mappedBy="pool")
     */
    private $groupPlayers;

    public function __construct()
    {
        $this->confrontation = new ArrayCollection();
        $this->groupPlayers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

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

    /**
     * @return Collection|Confrontation[]
     */
    public function getConfrontation(): Collection
    {
        return $this->confrontation;
    }

    public function addConfrontation(Confrontation $confrontation): self
    {
        if (!$this->confrontation->contains($confrontation)) {
            $this->confrontation[] = $confrontation;
        }

        return $this;
    }

    public function removeConfrontation(Confrontation $confrontation): self
    {
        $this->confrontation->removeElement($confrontation);

        return $this;
    }

    /**
     * @return Collection|GroupPlayer[]
     */
    public function getGroupPlayers(): Collection
    {
        return $this->groupPlayers;
    }

    public function addGroupPlayer(GroupPlayer $groupPlayer): self
    {
        if (!$this->groupPlayers->contains($groupPlayer)) {
            $this->groupPlayers[] = $groupPlayer;
            $groupPlayer->setPool($this);
        }

        return $this;
    }

    public function removeGroupPlayer(GroupPlayer $groupPlayer): self
    {
        if ($this->groupPlayers->removeElement($groupPlayer)) {
            // set the owning side to null (unless already changed)
            if ($groupPlayer->getPool() === $this) {
                $groupPlayer->setPool(null);
            }
        }

        return $this;
    }
}
