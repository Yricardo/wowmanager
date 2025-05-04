<?php

namespace App\Entity;

use App\Repository\EventTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventTypeRepository::class)]
class EventType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, EventLog>
     */
    #[ORM\OneToMany(targetEntity: EventLog::class, mappedBy: 'type', orphanRemoval: true)]
    private Collection $eventLogs;

    public function __construct()
    {
        $this->eventLogs = new ArrayCollection();
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
     * @return Collection<int, EventLog>
     */
    public function getEventLogs(): Collection
    {
        return $this->eventLogs;
    }

    public function addEventLog(EventLog $eventLog): static
    {
        if (!$this->eventLogs->contains($eventLog)) {
            $this->eventLogs->add($eventLog);
            $eventLog->setType($this);
        }

        return $this;
    }

    public function removeEventLog(EventLog $eventLog): static
    {
        if ($this->eventLogs->removeElement($eventLog)) {
            // set the owning side to null (unless already changed)
            if ($eventLog->getType() === $this) {
                $eventLog->setType(null);
            }
        }

        return $this;
    }
}
