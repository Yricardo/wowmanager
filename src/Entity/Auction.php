<?php

namespace App\Entity;

use App\Repository\AuctionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuctionRepository::class)]
class Auction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'auctions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Price $price = null;

    #[ORM\ManyToOne(inversedBy: 'auctions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $seller = null;

    #[ORM\Column]
    private ?int $soldQuantity = null;

    #[ORM\Column]
    private ?bool $buyout = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Price $minimumBuyPrice = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Price $buyoutPrice = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column]
    private ?int $durationHour = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $visibility = null;

    #[ORM\Column]
    private ?bool $openForBid = null;

    /**
     * @var Collection<int, AuctionBid>
     */
    #[ORM\OneToMany(targetEntity: AuctionBid::class, mappedBy: 'auction')]
    private Collection $auctionBids;

    public function __construct()
    {
        $this->auctionBids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSeller(): ?Character
    {
        return $this->seller;
    }

    public function setSeller(?Character $seller): static
    {
        $this->seller = $seller;

        return $this;
    }

    public function getSoldQuantity(): ?int
    {
        return $this->soldQuantity;
    }

    public function setSoldQuantity(int $soldQuantity): static
    {
        $this->soldQuantity = $soldQuantity;

        return $this;
    }

    public function isBuyout(): ?bool
    {
        return $this->buyout;
    }

    public function setBuyout(bool $buyout): static
    {
        $this->buyout = $buyout;

        return $this;
    }

    public function getMinimumBuyPrice(): ?Price
    {
        return $this->minimumBuyPrice;
    }

    public function setMinimumBuyPrice(Price $minimumBuyPrice): static
    {
        $this->minimumBuyPrice = $minimumBuyPrice;

        return $this;
    }

    public function getBuyoutPrice(): ?Price
    {
        return $this->buyoutPrice;
    }

    public function setBuyoutPrice(?Price $buyoutPrice): static
    {
        $this->buyoutPrice = $buyoutPrice;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getDurationHour(): ?int
    {
        return $this->durationHour;
    }

    public function setDurationHour(int $durationHour): static
    {
        $this->durationHour = $durationHour;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function isOpenForBid(): ?bool
    {
        return $this->openForBid;
    }

    public function setOpenForBid(bool $openForBid): static
    {
        $this->openForBid = $openForBid;

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
            $auctionBid->setAuction($this);
        }

        return $this;
    }

    public function removeAuctionBid(AuctionBid $auctionBid): static
    {
        if ($this->auctionBids->removeElement($auctionBid)) {
            // set the owning side to null (unless already changed)
            if ($auctionBid->getAuction() === $this) {
                $auctionBid->setAuction(null);
            }
        }

        return $this;
    }
}
