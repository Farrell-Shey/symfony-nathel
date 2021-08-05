<?php

namespace App\Entity;

use App\Repository\BanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BanRepository::class)
 */
class Ban
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Confrontation::class, inversedBy="bans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $confrontation;

    /**
     * @ORM\ManyToOne(targetEntity=MappoolMap::class, inversedBy="bans")
     */
    private $mappool_map;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_blue_side;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfrontation(): ?Confrontation
    {
        return $this->confrontation;
    }

    public function setConfrontation(?Confrontation $confrontation): self
    {
        $this->confrontation = $confrontation;

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

    public function getIsBlueSide(): ?bool
    {
        return $this->is_blue_side;
    }

    public function setIsBlueSide(bool $is_blue_side): self
    {
        $this->is_blue_side = $is_blue_side;

        return $this;
    }
}
