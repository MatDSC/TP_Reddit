<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/new/{postId}/{parentId?}', name: 'comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $postId, ?int $parentId, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setPost($entityManager->getRepository(\App\Entity\Post::class)->find($postId));
        if ($parentId) {
            $comment->setParentComment($entityManager->getRepository(Comment::class)->find($parentId));
        }
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        return $this->render('comment/new.html.twig', ['comment' => $comment, 'form' => $form]);
    }

    #[Route('/{id}/edit', name: 'comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($comment->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('comment/edit.html.twig', ['comment' => $comment, 'form' => $form]);
    }

    #[Route('/{id}', name: 'comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($comment->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
    }
}
