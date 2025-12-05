<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Form\Type\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/new/{postId}', name: 'comment_new', methods: ['POST'])]
    public function new(
        Request $request,
        int $postId,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $post = $postRepository->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('Publication non trouvé');
        }

        $user = $this->getUser();

        if (!$user->isConfirmed()) {
            $this->addFlash('error', 'Vous devez confirmer votre email avant de commenter.');
            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads/comments';
                    if (!file_exists($uploadsDir)) {
                        mkdir($uploadsDir, 0777, true);
                    }

                    $file->move($uploadsDir, $newFilename);
                    $comment->setFileName($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du fichier');
                }
            }

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès !');
            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        $this->addFlash('error', 'Erreur lors de l\'ajout du commentaire');
        return $this->redirectToRoute('post_show', ['id' => $postId]);
    }

    #[Route('/{id}', name: 'comment_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Comment $comment,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($comment->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            if ($comment->getFileName()) {
                $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/comments/' . $comment->getFileName();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $postId = $comment->getPost()->getId();
            $entityManager->remove($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire supprimé avec succès !');
            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        $this->addFlash('error', 'Token CSRF invalide');
        return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
    }
}
