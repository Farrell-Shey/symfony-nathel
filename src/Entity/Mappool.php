<?php

namespace App\Entity;

use App\Repository\MappoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MappoolRepository::class)
 */
class Mappool
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="integer")
     */
    private $follow;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=MappoolMap::class, mappedBy="mappool")
     */
    private $mappoolMaps;

    /**
     * @ORM\OneToMany(targetEntity=Step::class, mappedBy="mappool")
     */
    private $steps;

    public function __construct()
    {
        $this->mappoolMaps = new ArrayCollection();
        $this->steps = new ArrayCollection();
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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getFollow(): ?int
    {
        return $this->follow;
    }

    public function setFollow(int $follow): self
    {
        $this->follow = $follow;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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
            $mappoolMap->setMappool($this);
        }

        return $this;
    }

    public function removeMappoolMap(MappoolMap $mappoolMap): self
    {
        if ($this->mappoolMaps->removeElement($mappoolMap)) {
            // set the owning side to null (unless already changed)
            if ($mappoolMap->getMappool() === $this) {
                $mappoolMap->setMappool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Step[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setMappool($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getMappool() === $this) {
                $step->setMappool(null);
            }
        }

        return $this;
    }
}
