<?php

namespace App\Entity;

use App\Repository\BlacklistedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlacklistedRepository::class)
 */
class Blacklisted
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
    private $reason;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_admin_approved;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_ban_all_over;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $severity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getIsAdminApproved(): ?bool
    {
        return $this->is_admin_approved;
    }

    public function setIsAdminApproved(?bool $is_admin_approved): self
    {
        $this->is_admin_approved = $is_admin_approved;

        return $this;
    }

    public function getIsBanAllOver(): ?bool
    {
        return $this->is_ban_all_over;
    }

    public function setIsBanAllOver(?bool $is_ban_all_over): self
    {
        $this->is_ban_all_over = $is_ban_all_over;

        return $this;
    }

    public function getSeverity(): ?string
    {
        return $this->severity;
    }

    public function setSeverity(string $severity): self
    {
        $this->severity = $severity;

        return $this;
    }
}
