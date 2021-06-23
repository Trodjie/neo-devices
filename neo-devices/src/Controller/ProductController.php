<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'product_')]
class ProductController extends AbstractController
{
    const PRODUCTS_PER_PAGE = 6;

    #[Route('/category/{id}', name: 'category', requirements: ['id' => '\d+'])]
    public function category(int $id, Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $category = $em->find(Category::class, $id);

        if (null === $category) {
            throw $this->createNotFoundException(sprintf('Category with ID %d not found', $id));
        }

        /** @var \App\Repository\ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);

        $pagination = $paginator->paginate(
            $productRepository->getQueryBuilderForCategory($category),
            $request->query->getInt('page', 1),
            self::PRODUCTS_PER_PAGE
        );

        return $this->render('product/category.html.twig', [
            'category' => $category,
            'products' => $pagination->getItems(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/product/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $product = $em->find(Product::class, $id);

        if (null === $product) {
            throw $this->createNotFoundException(sprintf('Product with ID %d not found', $id));
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
