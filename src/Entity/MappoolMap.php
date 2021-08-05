<?php

namespace App\Entity;

use App\Repository\MappoolMapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MappoolMapRepository::class)
 */
class MappoolMap
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Mappool::class, inversedBy="mappoolMaps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mappool;

    /**
     * @ORM\ManyToOne(targetEntity=Beatmap::class, inversedBy="mappoolMaps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $beatmap;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mappoolMaps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode;

    /**
     * @ORM\OneToMany(targetEntity=Round::class, mappedBy="mappool_map")
     */
    private $rounds;

    /**
     * @ORM\OneToMany(targetEntity=Ban::class, mappedBy="mappool_map")
     */
    private $bans;

    public function __construct()
    {
        $this->rounds = new ArrayCollection();
        $this->bans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBeatmap(): ?Beatmap
    {
        return $this->beatmap;
    }

    public function setBeatmap(?Beatmap $beatmap): self
    {
        $this->beatmap = $beatmap;

        return $this;
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

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

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
            $round->setMappoolMap($this);
        }

        return $this;
    }

    public function removeRound(Round $round): self
    {
        if ($this->rounds->removeElement($round)) {
            // set the owning side to null (unless already changed)
            if ($round->getMappoolMap() === $this) {
                $round->setMappoolMap(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ban[]
     */
    public function getBans(): Collection
    {
        return $this->bans;
    }

    public function addBan(Ban $ban): self
    {
        if (!$this->bans->contains($ban)) {
            $this->bans[] = $ban;
            $ban->setMappoolMap($this);
        }

        return $this;
    }

    public function removeBan(Ban $ban): self
    {
        if ($this->bans->removeElement($ban)) {
            // set the owning side to null (unless already changed)
            if ($ban->getMappoolMap() === $this) {
                $ban->setMappoolMap(null);
            }
        }

        return $this;
    }
}
