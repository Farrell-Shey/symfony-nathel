<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
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
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=PoolSet::class, inversedBy="tags")
     */
    private $poolSets;

    public function __construct()
    {
        $this->poolSets = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|PoolSet[]
     */
    public function getPoolSets(): Collection
    {
        return $this->poolSets;
    }

    public function addPoolSet(PoolSet $poolSet): self
    {
        if (!$this->poolSets->contains($poolSet)) {
            $this->poolSets[] = $poolSet;
        }

        return $this;
    }

    public function removePoolSet(PoolSet $poolSet): self
    {
        $this->poolSets->removeElement($poolSet);

        return $this;
    }
}
