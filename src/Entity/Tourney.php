<?php

namespace App\Entity;

use App\Repository\TourneyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TourneyRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Tourney
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
    private $acronym;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $iteration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $background_home;

    /**
     * @ORM\Column(type="integer")
     */
    private $follow;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_players;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nb_inscrits;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discord;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forum_post;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_scorev2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $is_scale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_team;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_qualif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $groupstages;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bracket_format;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_pt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_reg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $round_of;

    /**
     * @ORM\Column(type="datetime")
     */
    private $reg_start_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $reg_close_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color_theme;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

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

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): self
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIteration(): ?int
    {
        return $this->iteration;
    }

    public function setIteration(int $iteration): self
    {
        $this->iteration = $iteration;

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

    public function getBackgroundHome(): ?string
    {
        return $this->background_home;
    }

    public function setBackgroundHome(?string $background_home): self
    {
        $this->background_home = $background_home;

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

    public function getNbPlayers(): ?int
    {
        return $this->nb_players;
    }

    public function setNbPlayers(int $nb_players): self
    {
        $this->nb_players = $nb_players;

        return $this;
    }

    public function getNbInscrits(): ?string
    {
        return $this->nb_inscrits;
    }

    public function setNbInscrits(string $nb_inscrits): self
    {
        $this->nb_inscrits = $nb_inscrits;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(?string $discord): self
    {
        $this->discord = $discord;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getForumPost(): ?string
    {
        return $this->forum_post;
    }

    public function setForumPost(?string $forum_post): self
    {
        $this->forum_post = $forum_post;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getis_scorev2(): ?bool
    {
        return $this->is_scorev2;
    }

    public function setis_scorev2(bool $is_scorev2): self
    {
        $this->is_scorev2 = $is_scorev2;

        return $this;
    }

    public function getis_scale(): ?string
    {
        return $this->is_scale;
    }

    public function setis_scale(?string $is_scale): self
    {
        $this->is_scale = $is_scale;

        return $this;
    }

    public function getis_team(): ?bool
    {
        return $this->is_team;
    }

    public function setis_team(bool $is_team): self
    {
        $this->is_team = $is_team;

        return $this;
    }

    public function getis_qualif(): ?bool
    {
        return $this->is_qualif;
    }

    public function setis_qualif(bool $is_qualif): self
    {
        $this->is_qualif = $is_qualif;

        return $this;
    }

    public function getGroupstages(): ?bool
    {
        return $this->groupstages;
    }

    public function setGroupstages(bool $groupstages): self
    {
        $this->groupstages = $groupstages;

        return $this;
    }

    public function getBracketFormat(): ?string
    {
        return $this->bracket_format;
    }

    public function setBracketFormat(string $bracket_format): self
    {
        $this->bracket_format = $bracket_format;

        return $this;
    }

    public function getMaxPt(): ?int
    {
        return $this->max_pt;
    }

    public function setMaxPt(?int $max_pt): self
    {
        $this->max_pt = $max_pt;

        return $this;
    }

    public function getMaxReg(): ?int
    {
        return $this->max_reg;
    }

    public function setMaxReg(?int $max_reg): self
    {
        $this->max_reg = $max_reg;

        return $this;
    }

    public function getRoundOf(): ?int
    {
        return $this->round_of;
    }

    public function setRoundOf(?int $round_of): self
    {
        $this->round_of = $round_of;

        return $this;
    }

    public function getRegStartDate(): ?\DateTimeInterface
    {
        return $this->reg_start_date;
    }

    public function setRegStartDate(\DateTimeInterface $reg_start_date): self
    {
        $this->reg_start_date = $reg_start_date;

        return $this;
    }

    public function getRegCloseDate(): ?\DateTimeInterface
    {
        return $this->reg_close_date;
    }

    public function setRegCloseDate(\DateTimeInterface $reg_close_date): self
    {
        $this->reg_close_date = $reg_close_date;

        return $this;
    }

    public function getColorTheme(): ?string
    {
        return $this->color_theme;
    }

    public function setColorTheme(string $color_theme): self
    {
        $this->color_theme = $color_theme;

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

    /*
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /*
     * Gets triggered only on update
     * @ORM\PreUpdate()
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}
