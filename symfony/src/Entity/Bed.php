<?php

namespace App\Entity;

use App\Repository\BedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: BedRepository::class)]
#[ORM\Table(name: 'beds')]
class Bed implements JsonSerializable
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'beds')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Room $room = null;

    #[ORM\Column(length: 10)]
    private ?string $label = null;

    #[ORM\Column(length: 50)]
    private ?string $bedType = null;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'bed')]
    private Collection $bookings;

    /**
     * Bed constructor.
     */
    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Room|null
     */
    public function getRoom(): ?Room
    {
        return $this->room;
    }

    /**
     * @param Room $room
     * @return $this
     */
    public function setRoom(Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBedType(): ?string
    {
        return $this->bedType;
    }

    /**
     * @param string $bedType
     * @return $this
     */
    public function setBedType(string $bedType): static
    {
        $this->bedType = $bedType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'        => $this->id,
            'room_id'   => $this->room?->getId(),
            'label'     => $this->label,
            'bed_type'  => $this->bedType,
            'is_active' => $this->isActive
        ];
    }
}
