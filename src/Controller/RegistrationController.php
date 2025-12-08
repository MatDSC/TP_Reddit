<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getEmail()) {
                $this->addFlash('error', 'Email est requis');
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $user->setRoles(['ROLE_USER']);

            $token = bin2hex(random_bytes(32));
            $user->setActivationToken($token);
            $user->setIsConfirmed(false);

            $entityManager->persist($user);
            $entityManager->flush();

            try {
                $emailService->sendValidationEmail(
                    $user->getEmail(),
                    $user->getUsername(),
                    $token
                );

                $this->addFlash('success', "Un email d'activation a été envoyé à votre adresse.");

            } catch (\Exception $e) {
                $this->addFlash('warning', "Compte créé mais l'email n'a pas pu être envoyé.");
            }

            return $this->redirectToRoute('app_login');

        }

        return $this->render('registration/register.html.twig', ['registrationForm' => $form->createView()]);
    }

    #[Route('/verify/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['activationToken' => $token]);
        if (!$user) {
            $this->addFlash('error', 'Token invalide ou expiré.');
            return $this->redirectToRoute('app_login');
        }

        if ($user->isTokenExpired()) {
            $this->addFlash('error', 'Le token a expiré. Veuillez demander un nouveau lien d\'activation.');
            return $this->redirectToRoute('app_login');
        }

        $user->setIsConfirmed(true);
        $user->setActivationToken(null);
        $user->setTokenExpiresAt(null);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été activé avec succès!');
        return $this->redirectToRoute('app_login');
    }
}
