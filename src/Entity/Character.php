<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: 'game_character')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $level = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Server $server = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterClass $characterClass = null;

    #[ORM\ManyToOne(inversedBy: 'charactersWithRole')]
    private ?CharacterRole $chosenRole = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    private ?Race $race = null;

    /**
     * @var Collection<int, OwnedItem>
     */
    #[ORM\OneToMany(targetEntity: OwnedItem::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $ownedItems;

    /**
     * @var Collection<int, Auction>
     */
    #[ORM\OneToMany(targetEntity: Auction::class, mappedBy: 'seller', orphanRemoval: true)]
    private Collection $auctions;

    /**
     * @var Collection<int, AuctionBid>
     */
    #[ORM\OneToMany(targetEntity: AuctionBid::class, mappedBy: 'bidder')]
    private Collection $auctionBids;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    private ?Guild $guild = null;

    #[ORM\Column(nullable: true)]
    private ?int $gearLevel = null;

    public function __construct()
    {
        $this->ownedItems = new ArrayCollection();
        $this->auctions = new ArrayCollection();
        $this->auctionBids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): static
    {
        $this->server = $server;

        return $this;
    }

    public function getCharacterClass(): ?CharacterClass
    {
        return $this->characterClass;
    }

    public function setCharacterClass(?CharacterClass $characterClass): static
    {
        $this->characterClass = $characterClass;

        return $this;
    }

    public function getChosenRole(): ?CharacterRole
    {
        return $this->chosenRole;
    }

    public function setChosenRole(?CharacterRole $chosenRole): static
    {
        $this->chosenRole = $chosenRole;

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): static
    {
        $this->race = $race;

        return $this;
    }

    /**
     * @return Collection<int, OwnedItem>
     */
    public function getOwnedItems(): Collection
    {
        return $this->ownedItems;
    }

    public function addOwnedItem(Item $ownedItem): static
    {
        $ownedItem = (new OwnedItem())
        ->setCharacter($this)
        ->setItem($ownedItem);

        if (!$this->ownedItems->contains($ownedItem)) {
            $this->ownedItems->add($ownedItem);
        }

        return $this;
    }

    public function removeOwnedItem(OwnedItem $ownedItem): static
    {
        if ($this->ownedItems->removeElement($ownedItem)) {
            // set the owning side to null (unless already changed)
            if ($ownedItem->getCharacter() === $this) {
                $ownedItem->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Auction>
     */
    public function getAuctions(): Collection
    {
        return $this->auctions;
    }

    public function addAuction(Auction $auction): static
    {
        if (!$this->auctions->contains($auction)) {
            $this->auctions->add($auction);
            $auction->setSeller($this);
        }

        return $this;
    }

    public function removeAuction(Auction $auction): static
    {
        if ($this->auctions->removeElement($auction)) {
            // set the owning side to null (unless already changed)
            if ($auction->getSeller() === $this) {
                $auction->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AuctionBid>
     */
    public function getAuctionBids(): Collection
    {
        return $this->auctionBids;
    }

    public function addAuctionBid(AuctionBid $auctionBid): static
    {
        if (!$this->auctionBids->contains($auctionBid)) {
            $this->auctionBids->add($auctionBid);
            $auctionBid->setBidder($this);
        }

        return $this;
    }

    public function removeAuctionBid(AuctionBid $auctionBid): static
    {
        if ($this->auctionBids->removeElement($auctionBid)) {
            // set the owning side to null (unless already changed)
            if ($auctionBid->getBidder() === $this) {
                $auctionBid->setBidder(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    public function setGuild(?Guild $guild): static
    {
        $this->guild = $guild;

        return $this;
    }

    public function getGearLevel(): ?int
    {
        return $this->gearLevel;
    }

    public function setGearLevel(?int $gearLevel): static
    {
        $this->gearLevel = $gearLevel;

        return $this;
    }
}
