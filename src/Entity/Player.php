<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayersRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Tourney::class, inversedBy="player")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tourney;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=GroupPlayer::class, mappedBy="player")
     */
    private $groupPlayers;

    /**
     * @ORM\OneToMany(targetEntity=TeamUser::class, mappedBy="player")
     */
    private $teamUsers;

    /**
     * @ORM\OneToMany(targetEntity=Round::class, mappedBy="player")
     */
    private $rounds;

    public function __construct()
    {
        $this->groupPlayers = new ArrayCollection();
        $this->teamUsers = new ArrayCollection();
        $this->rounds = new ArrayCollection();
    }

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

    public function getTourney(): ?Tourney
    {
        return $this->tourney;
    }

    public function setTourney(?Tourney $tourney): self
    {
        $this->tourney = $tourney;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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
            $groupPlayer->setPlayer($this);
        }

        return $this;
    }

    public function removeGroupPlayer(GroupPlayer $groupPlayer): self
    {
        if ($this->groupPlayers->removeElement($groupPlayer)) {
            // set the owning side to null (unless already changed)
            if ($groupPlayer->getPlayer() === $this) {
                $groupPlayer->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TeamUser[]
     */
    public function getTeamUsers(): Collection
    {
        return $this->teamUsers;
    }

    public function addTeamUser(TeamUser $teamUser): self
    {
        if (!$this->teamUsers->contains($teamUser)) {
            $this->teamUsers[] = $teamUser;
            $teamUser->setPlayer($this);
        }

        return $this;
    }

    public function removeTeamUser(TeamUser $teamUser): self
    {
        if ($this->teamUsers->removeElement($teamUser)) {
            // set the owning side to null (unless already changed)
            if ($teamUser->getPlayer() === $this) {
                $teamUser->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Round[]
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function addRound(Round $round): self
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds[] = $round;
            $round->setPlayer($this);
        }

        return $this;
    }

    public function removeRound(Round $round): self
    {
        if ($this->rounds->removeElement($round)) {
            // set the owning side to null (unless already changed)
            if ($round->getPlayer() === $this) {
                $round->setPlayer(null);
            }
        }

        return $this;
    }
}
