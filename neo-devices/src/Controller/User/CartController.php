<?php declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('', name: 'show')]
    public function show(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $repository = $em->getRepository(CartItem::class);

        $cartItems = $repository->findBy(['user' => $user]);

        return $this->render('cart/show.html.twig', [
            'cart_items' => $cartItems,
            'total_cost' => array_reduce($cartItems, static fn($sum, CartItem $item) => $sum += $item->getPrice()),
        ]);
    }

    #[Route('/add/{id}', name: 'add_item', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function addItem(int $id, EntityManagerInterface $em): Response
    {
        $product = $em->find(Product::class, $id);

        if (null === $product) {
            throw $this->createNotFoundException(sprintf('Product with ID %d not found', $id));
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $cartItemRepository = $em->getRepository(CartItem::class);
        $cartItem           = $cartItemRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if (null !== $cartItem) {
            $newCount = $cartItem->getCount() + 1;

            if ($newCount > $product->getQuantity()) {
                $this->addFlash('error', 'Не удалось добавить товар в корзину');

                return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
            }

            $cartItem->setCount($newCount);
        } else {
            $em->persist(new CartItem($user, $product));
        }

        $em->flush();

        $this->addFlash('success', 'Товар успешно добавлен в корзину');

        return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
    }

    #[Route('/remove/{id}', name: 'remove_item', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function removeItem(int $id, EntityManagerInterface $em): Response
    {
        $cartItem = $em->find(CartItem::class, $id);

        if (null === $cartItem) {
            throw $this->createNotFoundException(sprintf('Cart item with ID %d not found', $id));
        }

        $em->remove($cartItem);
        $em->flush();

        $this->addFlash('success', 'Товар успешно удален из корзины');

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/change-count/{id}', name: 'change_item_count', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function changeCount(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $cartItem = $em->find(CartItem::class, $id);

        if (null === $cartItem) {
            throw $this->createNotFoundException(sprintf('Cart item with ID %d not found', $id));
        }

        $count = $request->request->getInt('count');

        $cartItem->setCount($count);

        $em->flush();

        return $this->redirectToRoute('cart_show');
    }
}
