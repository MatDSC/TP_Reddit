<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]

    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('user/index.html.twig', ['user' => $userRepository->findAll()]);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', ['user' => $user, 'form' => $form]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            $subreddits = $entityManager->getRepository(\App\Entity\Subreddit::class)
                ->findBy(['createdBy' => $user]);

            if (count($subreddits) > 0) {
                $this->addFlash('error', "Impossible de supprimer cet utilisateur car il a créé des subreddits. Supprimez d'abord ses subreddits ou réassignez-les.");
                return $this->redirectToRoute('user_index');
            }

            $posts = $entityManager->getRepository(\App\Entity\Post::class)
                ->findBy(['author' => $user]);

            if (count($posts) > 0) {
                $this->addFlash('error', "Impossible de supprimer cet utilisateur car il a créé des publications. Supprimez d'abord ses publications.");
                return $this->redirectToRoute('user_index');
            }

            $comments = $entityManager->getRepository(\App\Entity\Comment::class)
                ->findBy(['author' => $user]);

            if (count($comments) > 0) {
                $this->addFlash('error', "Impossible de supprimer cet utilisateur car il a créé des commentaires. Supprimez d'abord ses commentaires");
                return $this->redirectToRoute('user_index');
            }

            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        }

        return $this->redirectToRoute('user_index');
    }
}
