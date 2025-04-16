<?php

namespace App\Entity;

use App\Repository\AuctionBidRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuctionBidRepository::class)]
class AuctionBid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'auctionBids')]
    private ?Character $bidder = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Price $price = null;

    #[ORM\ManyToOne(inversedBy: 'auctionBids')]
    private ?Auction $auction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBidder(): ?Character
    {
        return $this->bidder;
    }

    public function setBidder(?Character $bidder): static
    {
        $this->bidder = $bidder;

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

    public function getAuction(): ?Auction
    {
        return $this->auction;
    }

    public function setAuction(?Auction $auction): static
    {
        $this->auction = $auction;

        return $this;
    }
}
