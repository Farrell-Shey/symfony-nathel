<?php

namespace App\Entity;

use App\Repository\MappoolFollowedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MappoolFollowedRepository::class)
 */
class MappoolFollowed
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mappoolFolloweds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Mappool::class, inversedBy="mappoolFolloweds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mappool;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_complete = false;

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

    public function getMappool(): ?Mappool
    {
        return $this->mappool;
    }

    public function setMappool(?Mappool $mappool): self
    {
        $this->mappool = $mappool;

        return $this;
    }

    public function getIsComplete(): ?bool
    {
        return $this->is_complete;
    }

    public function setIsComplete(?bool $is_complete): self
    {
        $this->is_complete = $is_complete;

        return $this;
    }
}
