<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("orders:read")
     */
    private $refCde;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("orders:read")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     * @Groups("orders:read")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("orders:read")
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=Orderline::class, mappedBy="ordernum", orphanRemoval=true)
     * @Groups("orders:read")
     */
    private $orderlines;

    public function __construct()
    {
        $this->orderlines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefCde(): ?string
    {
        return $this->refCde;
    }

    public function setRefCde(string $refCde): self
    {
        $this->refCde = $refCde;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, Orderline>
     */
    public function getOrderlines(): Collection
    {
        return $this->orderlines;
    }

    public function addOrderline(Orderline $orderline): self
    {
        if (!$this->orderlines->contains($orderline)) {
            $this->orderlines[] = $orderline;
            $orderline->setOrdernum($this);
        }

        return $this;
    }

    public function removeOrderline(Orderline $orderline): self
    {
        if ($this->orderlines->removeElement($orderline)) {
            // set the owning side to null (unless already changed)
            if ($orderline->getOrdernum() === $this) {
                $orderline->setOrdernum(null);
            }
        }

        return $this;
    }
}
