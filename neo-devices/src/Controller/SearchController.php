<?php declare(strict_types=1);

namespace App\Controller;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/search", name: 'search_')]
class SearchController extends AbstractController
{
    const ITEMS_PER_PAGE = 9;

    #[Route('', name: 'query')]
    public function search(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('q');

        if (empty($query)) {
            throw $this->createNotFoundException('Search query is empty');
        }

        /** @var \App\Repository\ProductRepository $repository */
        $repository = $em->getRepository(Product::class);

        $pagination = $paginator->paginate(
            $repository->getQueryBuilderForSearch($query),
            $request->query->getInt('page', 1),
            self::ITEMS_PER_PAGE
        );

        return $this->render('search/results.html.twig', [
            'products' => $pagination->getItems(),
            'pagination' => $pagination,
            'query' => $query,
        ]);
    }
}
