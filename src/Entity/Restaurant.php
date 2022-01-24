<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"restaurants_get", "tickets_get", "users_get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"restaurants_get", "tickets_get", "users_get"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("restaurants_get")
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups("restaurants_get")
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups("restaurants_get")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups("restaurants_get")
     */
    private $phone_number;

    /**
     * @ORM\Column(type="bigint")
     * @Groups("restaurants_get")
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("restaurants_get")
     */
    private $trade_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"restaurants_get", "tickets_get"})
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     * @Groups("restaurants_get")
     */
    private $time_interval;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("restaurants_get")
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("restaurants_get")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("restaurants_get")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="restaurant", orphanRemoval=true)
     * @Groups("restaurants_get")
     */
    private $ticket;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("restaurants_get")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="restaurant", cascade={"persist", "remove"})
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=CuisineType::class, inversedBy="restaurants")
     * @Groups("restaurants_get")
     */
    private $cuisine_type;

    public function __construct()
    {
        $this->cuisine_type = new ArrayCollection();
        $this->ticket = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getTradeName(): ?string
    {
        return $this->trade_name;
    }

    public function setTradeName(string $trade_name): self
    {
        $this->trade_name = $trade_name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getTimeInterval(): ?int
    {
        return $this->time_interval;
    }

    public function setTimeInterval(int $time_interval): self
    {
        $this->time_interval = $time_interval;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTicket(): ?Collection
    {
        return $this->ticket;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->ticket->contains($ticket)) {
            $this->ticket[] = $ticket;
            $ticket->setRestaurant($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->ticket->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getRestaurant() === $this) {
                $ticket->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        // unset the owning side of the relation if necessary
        if ($owner === null && $this->owner !== null) {
            $this->owner->setRestaurant(null);
        }

        // set the owning side of the relation if necessary
        if ($owner !== null && $owner->getRestaurant() !== $this) {
            $owner->setRestaurant($this);
        }

        $this->owner = $owner;

        return $this;
    }

    public function getCuisineType()
    {
        return $this->cuisine_type;
    }

    public function setCuisineType(?CuisineType $cuisine_type): self
    {
        $this->cuisine_type = $cuisine_type;

        return $this;
    }
}
