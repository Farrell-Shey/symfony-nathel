<?php

namespace App\Entity;

use App\Repository\TourneyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $nb_player;

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

    /**
     * @ORM\OneToMany(targetEntity=Confrontation::class, mappedBy="tourney")
     */
    private $confrontations;

    /**
     * @ORM\OneToMany(targetEntity=TourneyStaff::class, mappedBy="tourney")
     */
    private $tourneyStaff;

    /**
     * @ORM\OneToMany(targetEntity=Group::class, mappedBy="tourney")
     */
    private $pools;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="tourney")
     * @ORM\JoinColumn(nullable=true)
     */
    private $player;

    /**
     * @ORM\OneToMany(targetEntity=Lobbie::class, mappedBy="tourney")
     */
    private $lobbies;

    /**
     * @ORM\OneToMany(targetEntity=Widget::class, mappedBy="tourney")
     */
    private $widgets;

    /**
     * @ORM\OneToMany(targetEntity=Step::class, mappedBy="tourney")
     */
    private $steps;

    /**
     * @ORM\OneToMany(targetEntity=Announce::class, mappedBy="tourney")
     */
    private $announces;

    /**
     * @ORM\OneToMany(targetEntity=Blacklisted::class, mappedBy="tourney")
     */
    private $blacklisteds;

    /**
     * @ORM\ManyToOne(targetEntity=PoolSet::class, inversedBy="tourneys")
     */
    private $poolset;

    public function __construct()
    {
        $this->confrontations = new ArrayCollection();
        $this->tourneyStaff = new ArrayCollection();
        $this->pools = new ArrayCollection();
        $this->player = new ArrayCollection();
        $this->lobbies = new ArrayCollection();
        $this->widgets = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->announces = new ArrayCollection();
        $this->blacklisteds = new ArrayCollection();
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

    public function getNbplayer(): ?int
    {
        return $this->nb_player;
    }

    public function setNbplayer(int $nb_player): self
    {
        $this->nb_player = $nb_player;

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
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    /*
     * Gets triggered only on update
     * @ORM\PreUpdate()
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime();
    }

    /**
     * @return Collection|Confrontation[]
     */
    public function getConfrontations(): Collection
    {
        return $this->confrontations;
    }

    public function addConfrontation(Confrontation $confrontation): self
    {
        if (!$this->confrontations->contains($confrontation)) {
            $this->confrontations[] = $confrontation;
            $confrontation->setTourney($this);
        }

        return $this;
    }

    public function removeConfrontation(Confrontation $confrontation): self
    {
        if ($this->confrontations->removeElement($confrontation)) {
            // set the owning side to null (unless already changed)
            if ($confrontation->getTourney() === $this) {
                $confrontation->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TourneyStaff[]
     */
    public function getTourneyStaff(): Collection
    {
        return $this->tourneyStaff;
    }

    public function addTourneyStaff(TourneyStaff $tourneyStaff): self
    {
        if (!$this->tourneyStaff->contains($tourneyStaff)) {
            $this->tourneyStaff[] = $tourneyStaff;
            $tourneyStaff->setTourney($this);
        }

        return $this;
    }

    public function removeTourneyStaff(TourneyStaff $tourneyStaff): self
    {
        if ($this->tourneyStaff->removeElement($tourneyStaff)) {
            // set the owning side to null (unless already changed)
            if ($tourneyStaff->getTourney() === $this) {
                $tourneyStaff->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getPools(): Collection
    {
        return $this->pools;
    }

    public function addPool(Group $pool): self
    {
        if (!$this->pools->contains($pool)) {
            $this->pools[] = $pool;
            $pool->setTourney($this);
        }

        return $this;
    }

    public function removePool(Group $pool): self
    {
        if ($this->pools->removeElement($pool)) {
            // set the owning side to null (unless already changed)
            if ($pool->getTourney() === $this) {
                $pool->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayer(): Collection
    {
        return $this->player;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->player->contains($player)) {
            $this->player[] = $player;
            $player->setTourney($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->player->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTourney() === $this) {
                $player->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Lobbie[]
     */
    public function getLobbies(): Collection
    {
        return $this->lobbies;
    }

    public function addLobby(Lobbie $lobby): self
    {
        if (!$this->lobbies->contains($lobby)) {
            $this->lobbies[] = $lobby;
            $lobby->setTourney($this);
        }

        return $this;
    }

    public function removeLobby(Lobbie $lobby): self
    {
        if ($this->lobbies->removeElement($lobby)) {
            // set the owning side to null (unless already changed)
            if ($lobby->getTourney() === $this) {
                $lobby->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Widget[]
     */
    public function getWidgets(): Collection
    {
        return $this->widgets;
    }

    public function addWidget(Widget $widget): self
    {
        if (!$this->widgets->contains($widget)) {
            $this->widgets[] = $widget;
            $widget->setTourney($this);
        }

        return $this;
    }

    public function removeWidget(Widget $widget): self
    {
        if ($this->widgets->removeElement($widget)) {
            // set the owning side to null (unless already changed)
            if ($widget->getTourney() === $this) {
                $widget->setTourney(null);
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
            $step->setTourney($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getTourney() === $this) {
                $step->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Announce[]
     */
    public function getAnnounces(): Collection
    {
        return $this->announces;
    }

    public function addAnnounce(Announce $announce): self
    {
        if (!$this->announces->contains($announce)) {
            $this->announces[] = $announce;
            $announce->setTourney($this);
        }

        return $this;
    }

    public function removeAnnounce(Announce $announce): self
    {
        if ($this->announces->removeElement($announce)) {
            // set the owning side to null (unless already changed)
            if ($announce->getTourney() === $this) {
                $announce->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blacklisted[]
     */
    public function getBlacklisteds(): Collection
    {
        return $this->blacklisteds;
    }

    public function addBlacklisted(Blacklisted $blacklisted): self
    {
        if (!$this->blacklisteds->contains($blacklisted)) {
            $this->blacklisteds[] = $blacklisted;
            $blacklisted->setTourney($this);
        }

        return $this;
    }

    public function removeBlacklisted(Blacklisted $blacklisted): self
    {
        if ($this->blacklisteds->removeElement($blacklisted)) {
            // set the owning side to null (unless already changed)
            if ($blacklisted->getTourney() === $this) {
                $blacklisted->setTourney(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoolset()
    {
        return $this->poolset;
    }

    /**
     * @param mixed $poolset
     */
    public function setPoolset($poolset): void
    {
        $this->poolset = $poolset;
    }
}
