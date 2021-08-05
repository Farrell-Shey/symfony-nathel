<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $osu_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discord;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $timezone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $silver_ss;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count_ss;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count_silver_s;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count_s;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count_a;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $game_mode_std;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $game_mode_mania;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $game_mode_taiko;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $game_mode_ctb;

    /**
     * @ORM\OneToMany(targetEntity=TourneyStaff::class, mappedBy="user")
     */
    private $tourneyStaff;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitch;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="user")
     */
    private $player;

    public function __construct()
    {
        $this->tourneyStaff = new ArrayCollection();
        $this->player = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOsuId(): ?int
    {
        return $this->osu_id;
    }

    public function setOsuId(int $osu_id): self
    {
        $this->osu_id = $osu_id;

        return $this;
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getTimezone(): ?int
    {
        return $this->timezone;
    }

    public function setTimezone(?int $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSilverSs(): ?int
    {
        return $this->silver_ss;
    }

    public function setSilverSs(?int $silver_ss): self
    {
        $this->silver_ss = $silver_ss;

        return $this;
    }

    public function getCountSs(): ?int
    {
        return $this->count_ss;
    }

    public function setCountSs(?int $count_ss): self
    {
        $this->count_ss = $count_ss;

        return $this;
    }

    public function getCountSilverS(): ?int
    {
        return $this->count_silver_s;
    }

    public function setCountSilverS(?int $count_silver_s): self
    {
        $this->count_silver_s = $count_silver_s;

        return $this;
    }

    public function getCountS(): ?int
    {
        return $this->count_s;
    }

    public function setCountS(?int $count_s): self
    {
        $this->count_s = $count_s;

        return $this;
    }

    public function getCountA(): ?int
    {
        return $this->count_a;
    }

    public function setCountA(?int $count_a): self
    {
        $this->count_a = $count_a;

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

    public function getGameModeStd(): ?int
    {
        return $this->game_mode_std;
    }

    public function setGameModeStd(?int $game_mode_std): self
    {
        $this->game_mode_std = $game_mode_std;

        return $this;
    }

    public function getGameModeMania(): ?int
    {
        return $this->game_mode_mania;
    }

    public function setGameModeMania(?int $game_mode_mania): self
    {
        $this->game_mode_mania = $game_mode_mania;

        return $this;
    }

    public function getGameModeTaiko(): ?int
    {
        return $this->game_mode_taiko;
    }

    public function setGameModeTaiko(?int $game_mode_taiko): self
    {
        $this->game_mode_taiko = $game_mode_taiko;

        return $this;
    }

    public function getGameModeCtb(): ?int
    {
        return $this->game_mode_ctb;
    }

    public function setGameModeCtb(?int $game_mode_ctb): self
    {
        $this->game_mode_ctb = $game_mode_ctb;

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
            $tourneyStaff->setUser($this);
        }

        return $this;
    }

    public function removeTourneyStaff(TourneyStaff $tourneyStaff): self
    {
        if ($this->tourneyStaff->removeElement($tourneyStaff)) {
            // set the owning side to null (unless already changed)
            if ($tourneyStaff->getUser() === $this) {
                $tourneyStaff->setUser(null);
            }
        }

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

    public function getTwitch(): ?string
    {
        return $this->twitch;
    }

    public function setTwitch(?string $twitch): self
    {
        $this->twitch = $twitch;

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
            $player->setUser($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->player->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getUser() === $this) {
                $player->setUser(null);
            }
        }

        return $this;
    }
}
