<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoundRepository::class)
 */
class Round
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="rounds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @ORM\Column(type="integer")
     */
    private $encounter;

    /**
     * @ORM\ManyToOne(targetEntity=MappoolMap::class, inversedBy="rounds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mappool_map;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\Column(type="integer")
     */
    private $accuracy;

    /**
     * @ORM\Column(type="integer")
     */
    private $misscount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_v1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getEncounter(): ?int
    {
        return $this->encounter;
    }

    public function setEncounter(int $encounter): self
    {
        $this->encounter = $encounter;

        return $this;
    }

    public function getMappoolMap(): ?MappoolMap
    {
        return $this->mappool_map;
    }

    public function setMappoolMap(?MappoolMap $mappool_map): self
    {
        $this->mappool_map = $mappool_map;

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

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy(int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getMisscount(): ?int
    {
        return $this->misscount;
    }

    public function setMisscount(int $misscount): self
    {
        $this->misscount = $misscount;

        return $this;
    }

    public function getIsV1(): ?bool
    {
        return $this->is_v1;
    }

    public function setIsV1(bool $is_v1): self
    {
        $this->is_v1 = $is_v1;

        return $this;
    }
}
