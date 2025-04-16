<?php

namespace App\Entity;

use App\Repository\GuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuildRepository::class)]
class Guild
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'guilds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Server $server = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'guild')]
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): static
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Character $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setGuild($this);
        }

        return $this;
    }

    public function removeMember(Character $member): static
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getGuild() === $this) {
                $member->setGuild(null);
            }
        }

        return $this;
    }
}
