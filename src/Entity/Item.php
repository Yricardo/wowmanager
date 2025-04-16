<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $idWow = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgPath = null;

    /**
     * @var Collection<int, OwnedItem>
     */
    #[ORM\OneToMany(targetEntity: OwnedItem::class, mappedBy: 'item', orphanRemoval: true)]
    private Collection $ownedBy;

    /**
     * @var Collection<int, Auction>
     */
    #[ORM\OneToMany(targetEntity: Auction::class, mappedBy: 'item', orphanRemoval: true)]
    private Collection $auctions;

    public function __construct()
    {
        $this->ownedBy = new ArrayCollection();
        $this->auctions = new ArrayCollection();
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

    public function getIdWow(): ?int
    {
        return $this->idWow;
    }

    public function setIdWow(int $idWow): static
    {
        $this->idWow = $idWow;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): static
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    /**
     * @return Collection<int, OwnedItem>
     */
    public function getOwnedBy(): Collection
    {
        return $this->ownedBy;
    }

    public function addOwnedBy(OwnedItem $ownedBy): static
    {
        if (!$this->ownedBy->contains($ownedBy)) {
            $this->ownedBy->add($ownedBy);
            $ownedBy->setItem($this);
        }

        return $this;
    }

    public function removeOwnedBy(OwnedItem $ownedBy): static
    {
        if ($this->ownedBy->removeElement($ownedBy)) {
            // set the owning side to null (unless already changed)
            if ($ownedBy->getItem() === $this) {
                $ownedBy->setItem(null);
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
            $auction->setItem($this);
        }
        return $this;
    }

    public function removeAuction(Auction $auction): static
    {
        if ($this->auctions->removeElement($auction)) {
            // set the owning side to null (unless already changed)
            if ($auction->getItem() === $this) {
                $auction->setItem(null);
            }
        }

        return $this;
    }
}
