<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CartItem
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private Product $product;

    #[ORM\Column(type: 'integer')]
    private int $count;

    public function __construct(User $user, Product $product)
    {
        $this->user    = $user;
        $this->product = $product;
        $this->count   = 1;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getPrice(): float
    {
        return $this->product->getPrice() * $this->count;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): CartItem
    {
        $this->count = $count;

        return $this;
    }
}
