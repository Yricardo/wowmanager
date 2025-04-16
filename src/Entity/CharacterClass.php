<?php

namespace App\Entity;

use App\Repository\CharacterClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterClassRepository::class)]
class CharacterClass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'characterClass', orphanRemoval: true)]
    private Collection $characters;

    /**
     * @var Collection<int, AvailableRoleForClass>
     */
    #[ORM\OneToMany(targetEntity: AvailableRoleForClass::class, mappedBy: 'class', orphanRemoval: true)]
    private Collection $availableRoleForClass;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $classImgPath = null;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->AvailableRoleForClass = new ArrayCollection();
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

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->setCharacterClass($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getCharacterClass() === $this) {
                $character->setCharacterClass(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AvailableRoleForClass>
     */
    public function getAvailableRoleForClass(): Collection
    {
        return $this->availableRoleForClass;
    }

    public function addAvailableRoleForClass(AvailableRoleForClass $availableRoleForClass): static
    {
        if (!$this->availableRoleForClass->contains($availableRoleForClass)) {
            $this->availableRoleForClass->add($availableRoleForClass);
            $availableRoleForClass->setClass($this);
        }

        return $this;
    }

    public function removeAvailableRoleForClass(AvailableRoleForClass $availableRoleForClass): static
    {
        if ($this->availableRoleForClass->removeElement($availableRoleForClass)) {
            // set the owning side to null (unless already changed)
            if ($availableRoleForClass->getClass() === $this) {
                $availableRoleForClass->setClass(null);
            }
        }

        return $this;
    }

    public function getClassImgPath(): ?string
    {
        return $this->classImgPath;
    }

    public function setClassImgPath(?string $classImgPath): static
    {
        $this->classImgPath = $classImgPath;

        return $this;
    }
}
