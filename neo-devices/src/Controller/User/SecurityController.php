<?php declare(strict_types=1);

namespace App\Controller\User;

use App\DTO as DTO;
use App\Entity\User;
use App\Form\Type\Security\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

#[Route(name: 'security_')]
class SecurityController extends AbstractController
{
    #[Route('/registration', name: 'registration')]
    public function registration(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher, TokenStorageInterface $tokenStorage, EventDispatcherInterface $eventDispatcher): Response
    {
        $userDto = new DTO\User();
        $form    = $this->createForm(RegistrationFormType::class, $userDto)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User(
                $userDto->email,
                $userDto->name,
                $userDto->lastname,
                $userDto->address,
                $userDto->phone,
            );

            $user->setPassword($hasher->hashPassword($user, $userDto->rawPassword));

            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
            $tokenStorage->setToken($token);
            $eventDispatcher->dispatch(new InteractiveLoginEvent($request, $token));

            $this->addFlash('success', 'Учетная запись успешно зарегистрирована');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
