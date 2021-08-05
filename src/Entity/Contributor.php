<?php

namespace App\Entity;

use App\Repository\ContributorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContributorRepository::class)
 */
class Contributor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contributors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=PoolSet::class, inversedBy="contributors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $poolSet;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_creator = false;

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

    public function getPoolSet(): ?PoolSet
    {
        return $this->poolSet;
    }

    public function setPoolSet(?PoolSet $poolSet): self
    {
        $this->poolSet = $poolSet;

        return $this;
    }

    public function getIsCreator(): ?bool
    {
        return $this->is_creator;
    }

    public function setIsCreator(?bool $is_creator): self
    {
        $this->is_creator = $is_creator;

        return $this;
    }
}
