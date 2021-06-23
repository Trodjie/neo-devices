<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route("/", name: 'home')]
    public function home(EntityManagerInterface $em): Response
    {
        $productRepository = $em->getRepository(Product::class);

        $products = $productRepository->findBy(['showOnHomepage' => true]);

        return $this->render('page/home.html.twig', [
            'products' => $products,
            'slider_images' => [
                '/image/news/news1.png',
                '/image/news/news2.png',
                '/image/news/news3.png'
            ],
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }
}
