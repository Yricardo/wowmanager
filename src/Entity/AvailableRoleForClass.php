<?php

namespace App\Entity;

use App\Repository\AvailableRoleForClassRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvailableRoleForClassRepository::class)]
class AvailableRoleForClass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'availableRoleForClass')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterClass $class = null;

    #[ORM\ManyToOne(inversedBy: 'availableClassForRoles')]
    private ?CharacterRole $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClass(): ?CharacterClass
    {
        return $this->class;
    }

    public function setClass(?CharacterClass $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getRole(): ?CharacterRole
    {
        return $this->role;
    }

    public function setRole(?CharacterRole $role): static
    {
        $this->role = $role;

        return $this;
    }
}
