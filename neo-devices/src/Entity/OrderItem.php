<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class OrderItem
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private Product $product;

    #[ORM\Column(type: 'integer')]
    private int $count;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    private Order $order;

    public function __construct(Product $product, int $count)
    {
        $this->product = $product;
        $this->count   = $count;
    }

    public static function createFromCartItem(CartItem $cartItem): self
    {
        return new self($cartItem->getProduct(), $cartItem->getCount());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): OrderItem
    {
        $this->order = $order;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->product->getPrice() * $this->count;
    }
}
