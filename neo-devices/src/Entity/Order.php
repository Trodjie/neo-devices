<?php declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist'])]
    private Collection $items;

    public function __construct(User $user)
    {
        $this->user      = $user;
        $this->createdAt = new DateTimeImmutable();
        $this->items     = new ArrayCollection();
    }

    public function getTotalCost(): float
    {
        return array_reduce($this->items->toArray(), static fn(?float $sum, OrderItem $item) => $sum += $item->getPrice());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Order
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): Order
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|iterable<\App\Entity\OrderItem>
     */
    public function getItems(): array|Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $orderItem): self
    {
        if (!$this->items->contains($orderItem)) {
            $this->items->add($orderItem);
            $orderItem->setOrder($this);
        }

        return $this;
    }
}
