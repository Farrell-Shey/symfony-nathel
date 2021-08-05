<?php

namespace App\Entity;

use App\Repository\PoolSetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PoolSetRepository::class)
 */
class PoolSet
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
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\OneToMany(targetEntity=Contributor::class, mappedBy="poolSet", orphanRemoval=true)
     */
    private $contributors;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="poolSet")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="poolset", orphanRemoval=true)
     */
    private $invitations;

    /**
     * @ORM\OneToMany(targetEntity=Mappool::class, mappedBy="poolSet", orphanRemoval=true)
     */
    private $mappools;

    public function __construct()
    {
        $this->contributors = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->mappools = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return Collection|Contributor[]
     */
    public function getContributors(): Collection
    {
        return $this->contributors;
    }

    public function addContributor(Contributor $contributor): self
    {
        if (!$this->contributors->contains($contributor)) {
            $this->contributors[] = $contributor;
            $contributor->setPoolSet($this);
        }

        return $this;
    }

    public function removeContributor(Contributor $contributor): self
    {
        if ($this->contributors->removeElement($contributor)) {
            // set the owning side to null (unless already changed)
            if ($contributor->getPoolSet() === $this) {
                $contributor->setPoolSet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPoolSet($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePoolSet($this);
        }

        return $this;
    }

    /**
     * @return Collection|Invitation[]
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
            $invitation->setPoolset($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getPoolset() === $this) {
                $invitation->setPoolset(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mappool[]
     */
    public function getMappools(): Collection
    {
        return $this->mappools;
    }

    public function addMappool(Mappool $mappool): self
    {
        if (!$this->mappools->contains($mappool)) {
            $this->mappools[] = $mappool;
            $mappool->setPoolSet($this);
        }

        return $this;
    }

    public function removeMappool(Mappool $mappool): self
    {
        if ($this->mappools->removeElement($mappool)) {
            // set the owning side to null (unless already changed)
            if ($mappool->getPoolSet() === $this) {
                $mappool->setPoolSet(null);
            }
        }

        return $this;
    }
}
