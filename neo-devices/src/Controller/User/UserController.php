<?php declare(strict_types=1);

namespace App\Controller\User;


use App\DTO\Profile;
use App\Entity\Order;
use App\Form\Type\User\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $profile = Profile::createFromEntity($user);

        $form = $this->createForm(ProfileType::class, $profile)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->updateFromDto($profile);

            $em->flush();

            $this->addFlash('success', 'Данные успешно обновлены');
        }

        $oderRepository = $em->getRepository(Order::class);

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'orders' => $oderRepository->findBy(['user' => $user]),
        ]);
    }
}
