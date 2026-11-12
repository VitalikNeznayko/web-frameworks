<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ORM\Table(name: 'bookings')]
class Booking implements JsonSerializable
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Guest::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Guest $guest = null;

    #[ORM\ManyToOne(targetEntity: Bed::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Bed $bed = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $checkOut = null;

    #[ORM\Column(length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalPrice = null;

    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'booking')]
    private Collection $payments;

    /**
     * Booking constructor.
     */
    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Guest|null
     */
    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    /**
     * @param Guest $guest
     * @return $this
     */
    public function setGuest(Guest $guest): static
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * @return Bed|null
     */
    public function getBed(): ?Bed
    {
        return $this->bed;
    }

    /**
     * @param Bed $bed
     * @return $this
     */
    public function setBed(Bed $bed): static
    {
        $this->bed = $bed;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCheckIn(): ?DateTimeInterface
    {
        return $this->checkIn;
    }

    /**
     * @param DateTimeInterface $checkIn
     * @return $this
     */
    public function setCheckIn(DateTimeInterface $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCheckOut(): ?DateTimeInterface
    {
        return $this->checkOut;
    }

    /**
     * @param DateTimeInterface $checkOut
     * @return $this
     */
    public function setCheckOut(DateTimeInterface $checkOut): static
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    /**
     * @param string $totalPrice
     * @return $this
     */
    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'          => $this->id,
            'guest_id'    => $this->guest?->getId(),
            'bed_id'      => $this->bed?->getId(),
            'check_in'    => $this->checkIn?->format('Y-m-d'),
            'check_out'   => $this->checkOut?->format('Y-m-d'),
            'status'      => $this->status,
            'total_price' => $this->totalPrice
        ];
    }
}
