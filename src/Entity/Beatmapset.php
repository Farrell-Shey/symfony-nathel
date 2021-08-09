<?php

namespace App\Entity;

use App\Repository\BeatmapsetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeatmapsetRepository::class)
 */
class Beatmapset
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $artist;

    /**
     * @ORM\OneToMany(targetEntity=Beatmap::class, mappedBy="beatmapset", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $beatmaps;

    public function __construct()
    {
        $this->beatmaps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return Collection|Beatmap[]
     */
    public function getBeatmaps(): Collection
    {
        return $this->beatmaps;
    }

    public function addBeatmap(Beatmap $beatmap): self
    {
        if (!$this->beatmaps->contains($beatmap)) {
            $this->beatmaps[] = $beatmap;
            $beatmap->setBeatmapset($this);
        }

        return $this;
    }

    public function removeBeatmap(Beatmap $beatmap): self
    {
        if ($this->beatmaps->removeElement($beatmap)) {
            // set the owning side to null (unless already changed)
            if ($beatmap->getBeatmapset() === $this) {
                $beatmap->setBeatmapset(null);
            }
        }

        return $this;
    }
}
