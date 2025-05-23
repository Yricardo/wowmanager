<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{

    public const SETTING_TYPE_STRING = 'string';
    public const SETTING_TYPE_INT = 'int';
    public const SETTING_TYPE_FLOAT = 'float';
    public const SETTING_TYPE_BOOL = 'bool';
    public const SETTING_TYPE_ARRAY = 'array';
    public const SETTING_TYPE_JSON = 'json';
    public const SETTING_TYPE_OBJECT = 'object';
    public const SETTING_TYPE_DATE = 'date';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relatedEntity = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getRelatedEntity(): ?string
    {
        return $this->relatedEntity;
    }

    public function setRelatedEntity(string $relatedEntity): static
    {
        $this->relatedEntity = $relatedEntity;

        return $this;
    }
}
