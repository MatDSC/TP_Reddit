<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Type\CommentType;
use App\Repository\CommentRepository;
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
        $post = $postRepository->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $file = $form->get('file')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/comments',
                        $newFilename
                    );
                    $comment->setFileName($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload file: ' . $e->getMessage());
                }
            }

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Comment added successfully!');
            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        // If form has errors, redirect back with error message
        $this->addFlash('error', 'Failed to add comment. Please check your input.');
        return $this->redirectToRoute('post_show', ['id' => $postId]);
    }

    #[Route('/{id}/edit', name: 'comment_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Comment $comment,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        // Check permissions
        if ($comment->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot edit this comment.');
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $file = $form->get('file')->getData();

            if ($file) {
                // Delete old file if exists
                if ($comment->getFileName()) {
                    $oldFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/comments/' . $comment->getFileName();
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/comments',
                        $newFilename
                    );
                    $comment->setFileName($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload file: ' . $e->getMessage());
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Comment updated successfully!');
            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'comment_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Comment $comment,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Check permissions
        if ($comment->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot delete this comment.');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            // Delete file if exists
            if ($comment->getFileName()) {
                $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/comments/' . $comment->getFileName();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $postId = $comment->getPost()->getId();

            // Use repository method if you want (or just entityManager->remove)
            $entityManager->remove($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Comment deleted successfully!');
            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        $this->addFlash('error', 'Invalid CSRF token.');
        return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
    }

    #[Route('/{id}', name: 'comment_show', methods: ['GET'])]
    public function show(Comment $comment, CommentRepository $commentRepository): Response
    {
        // You can use repository to fetch additional data if needed
        // For example, get all replies to this comment
        $replies = $commentRepository->findBy(['parentComment' => $comment]);

        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
            'replies' => $replies,
        ]);
    }
}
