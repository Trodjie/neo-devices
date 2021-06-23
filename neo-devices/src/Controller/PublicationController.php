<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/news", name: 'news_')]
class PublicationController extends AbstractController
{
    private const PUBLICATIONS_PER_PAGE = 10;

    #[Route("", name: 'list')]
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        /** @var \App\Repository\PublicationRepository $repository */
        $repository = $em->getRepository(Publication::class);

        $pagination = $paginator->paginate($repository->getQueryBuilderForList(),
            $request->query->getInt('page', 1),
            self::PUBLICATIONS_PER_PAGE,
            []
        );

        return $this->render('publication/list.html.twig', [
            'publications' => $pagination->getItems(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $publication = $em->find(Publication::class, $id);

        if (null === $publication) {
            throw $this->createNotFoundException(sprintf('Publication with ID %d not found', $id));
        }

        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
        ]);
    }
}
