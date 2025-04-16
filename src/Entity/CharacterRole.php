<?php

namespace App\Entity;

use App\Repository\CharacterRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRoleRepository::class)]
class CharacterRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $imgPath = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'chosenRole')]
    private Collection $charactersWithRole;

    /**
     * @var Collection<int, AvailableRoleForClass>
     */
    #[ORM\OneToMany(targetEntity: AvailableRoleForClass::class, mappedBy: 'role')]
    private Collection $availableClassForRoles;

    public function __construct()
    {
        $this->charactersWithRole = new ArrayCollection();
        $this->availableClassForRoles = new ArrayCollection();
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

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(string $imgPath): static
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharactersWithRole(): Collection
    {
        return $this->charactersWithRole;
    }

    public function addCharactersWithRole(Character $charactersWithRole): static
    {
        if (!$this->charactersWithRole->contains($charactersWithRole)) {
            $this->charactersWithRole->add($charactersWithRole);
            $charactersWithRole->setChosenRole($this);
        }

        return $this;
    }

    public function removeCharactersWithRole(Character $charactersWithRole): static
    {
        if ($this->charactersWithRole->removeElement($charactersWithRole)) {
            // set the owning side to null (unless already changed)
            if ($charactersWithRole->getChosenRole() === $this) {
                $charactersWithRole->setChosenRole(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AvailableRoleForClass>
     */
    public function getAvailableClassForRoles(): Collection
    {
        return $this->availableClassForRoles;
    }

    public function addAvailableClassForRole(AvailableRoleForClass $availableClassForRole): static
    {
        if (!$this->availableClassForRoles->contains($availableClassForRole)) {
            $this->availableClassForRoles->add($availableClassForRole);
            $availableClassForRole->setRole($this);
        }

        return $this;
    }

    public function removeAvailableClassForRole(AvailableRoleForClass $availableClassForRole): static
    {
        if ($this->availableClassForRoles->removeElement($availableClassForRole)) {
            // set the owning side to null (unless already changed)
            if ($availableClassForRole->getRole() === $this) {
                $availableClassForRole->setRole(null);
            }
        }

        return $this;
    }
}
