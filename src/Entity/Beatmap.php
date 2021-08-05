<?php

namespace App\Entity;

use App\Repository\BeatmapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeatmapRepository::class)
 */
class Beatmap
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
    private $difficulty;

    /**
     * @ORM\Column(type="integer")
     */
    private $bpm;

    /**
     * @ORM\Column(type="integer")
     */
    private $ar;

    /**
     * @ORM\Column(type="integer")
     */
    private $cs;

    /**
     * @ORM\Column(type="integer")
     */
    private $drain;

    /**
     * @ORM\Column(type="integer")
     */
    private $accuracy;

    /**
     * @ORM\Column(type="integer")
     */
    private $hit_length;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode_int;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=MappoolMap::class, mappedBy="beatmap")
     */
    private $mappoolMaps;

    public function __construct()
    {
        $this->mappoolMaps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getBpm(): ?int
    {
        return $this->bpm;
    }

    public function setBpm(int $bpm): self
    {
        $this->bpm = $bpm;

        return $this;
    }

    public function getAr(): ?int
    {
        return $this->ar;
    }

    public function setAr(int $ar): self
    {
        $this->ar = $ar;

        return $this;
    }

    public function getCs(): ?int
    {
        return $this->cs;
    }

    public function setCs(int $cs): self
    {
        $this->cs = $cs;

        return $this;
    }

    public function getDrain(): ?int
    {
        return $this->drain;
    }

    public function setDrain(int $drain): self
    {
        $this->drain = $drain;

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

    public function getHitLength(): ?int
    {
        return $this->hit_length;
    }

    public function setHitLength(int $hit_length): self
    {
        $this->hit_length = $hit_length;

        return $this;
    }

    public function getModeInt(): ?string
    {
        return $this->mode_int;
    }

    public function setModeInt(string $mode_int): self
    {
        $this->mode_int = $mode_int;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|MappoolMap[]
     */
    public function getMappoolMaps(): Collection
    {
        return $this->mappoolMaps;
    }

    public function addMappoolMap(MappoolMap $mappoolMap): self
    {
        if (!$this->mappoolMaps->contains($mappoolMap)) {
            $this->mappoolMaps[] = $mappoolMap;
            $mappoolMap->setBeatmap($this);
        }

        return $this;
    }

    public function removeMappoolMap(MappoolMap $mappoolMap): self
    {
        if ($this->mappoolMaps->removeElement($mappoolMap)) {
            // set the owning side to null (unless already changed)
            if ($mappoolMap->getBeatmap() === $this) {
                $mappoolMap->setBeatmap(null);
            }
        }

        return $this;
    }
}
