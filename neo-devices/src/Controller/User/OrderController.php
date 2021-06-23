<?php declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/order', name: 'order_')]
class OrderController extends AbstractController
{
    #[Route('', name: 'make', methods: ['post'])]
    public function order(EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $repository = $em->getRepository(CartItem::class);

        $order = new Order($user);

        foreach ($repository->findBy(['user' => $user]) as $cartItem) {
            $product = $cartItem->getProduct();

            $order->addItem(OrderItem::createFromCartItem($cartItem));
            $product->setQuantity($product->getQuantity() - $cartItem->getCount());

            $em->remove($cartItem);
        }

        $em->persist($order);
        $em->flush();

        $this->addFlash('success', 'Заказ успешно оформлен');

        return $this->redirectToRoute('user_profile');
    }
}
