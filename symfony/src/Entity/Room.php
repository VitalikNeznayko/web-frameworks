<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ORM\Table(name: 'rooms')]
#[UniqueEntity(fields: ['number'], message: 'Кімната з таким номером вже існує.')]
class Room
{
    public const TYPES = ['dorm', 'private'];
    public const TYPE_LABELS = ['dorm' => 'Загальна', 'private' => 'Приватна'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 10)]
    private ?string $number = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: self::TYPES)]
    private ?string $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private ?int $floor = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?string $pricePerNight = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Bed>
     */
    #[ORM\OneToMany(targetEntity: Bed::class, mappedBy: 'room', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['label' => 'ASC'])]
    private Collection $beds;

    /**
     * @var Collection<int, Amenity>
     */
    #[ORM\ManyToMany(targetEntity: Amenity::class, inversedBy: 'rooms')]
    #[ORM\JoinTable(name: 'room_amenity')]
    private Collection $amenities;

    public function __construct()
    {
        $this->beds = new ArrayCollection();
        $this->amenities = new ArrayCollection();
    }

    public function __toString(): string
    {
        return 'Кімната '.$this->number;
    }

    public function getTypeLabel(): string
    {
        return self::TYPE_LABELS[$this->type] ?? (string) $this->type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): static
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPricePerNight(): ?string
    {
        return $this->pricePerNight;
    }

    public function setPricePerNight(?string $pricePerNight): static
    {
        $this->pricePerNight = $pricePerNight;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Bed>
     */
    public function getBeds(): Collection
    {
        return $this->beds;
    }

    public function addBed(Bed $bed): static
    {
        if (!$this->beds->contains($bed)) {
            $this->beds->add($bed);
            $bed->setRoom($this);
        }

        return $this;
    }

    public function removeBed(Bed $bed): static
    {
        if ($this->beds->removeElement($bed) && $bed->getRoom() === $this) {
            $bed->setRoom(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Amenity>
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(Amenity $amenity): static
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
            $amenity->addRoom($this);
        }

        return $this;
    }

    public function removeAmenity(Amenity $amenity): static
    {
        if ($this->amenities->removeElement($amenity)) {
            $amenity->removeRoom($this);
        }

        return $this;
    }
}
