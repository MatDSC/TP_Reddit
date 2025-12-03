<?php
namespace App\Controller;

use App\Entity\Subreddit;
use App\Form\Type\SubredditType;
use App\Repository\SubredditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subreddit')]
class SubredditController extends AbstractController
{
    #[Route('/', name: 'subreddit_index', methods: ['GET'])]
    public function index(SubredditRepository $subredditRepository): Response
    {
        return $this->render('subreddit/index.html.twig', ['subreddits' => $subredditRepository->findAll()]);
    }

    #[Route('/new', name: 'subreddit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subreddit = new Subreddit();
        $subreddit->setCreatedBy($this->getUser());
        $form = $this->createForm(SubredditType::class, $subreddit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subreddit);
            $entityManager->flush();
            return $this->redirectToRoute('subreddit_index');
        }

        return $this->render('subreddit/new.html.twig', ['subreddit' => $subreddit, 'form' => $form]);
    }

    #[Route('/{id}', name: 'subreddit_show', methods: ['GET'])]
    public function show(Subreddit $subreddit): Response
    {
        return $this->render('subreddit/show.html.twig', ['subreddit' => $subreddit]);
    }

    #[Route('/{id}/edit', name: 'subreddit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subreddit $subreddit, EntityManagerInterface $entityManager): Response
    {
        if (!$subreddit->getModerators()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(SubredditType::class, $subreddit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('subreddit_show', ['id' => $subreddit->getId()]);
        }

        return $this->render('subreddit/edit.html.twig', ['subreddit' => $subreddit, 'form' => $form]);
    }

    #[Route('/{id}', name: 'subreddit_delete', methods: ['POST'])]
    public function delete(Request $request, Subreddit $subreddit, EntityManagerInterface $entityManager): Response
    {
        if (!$subreddit->getModerators()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->isCsrfTokenValid('delete'.$subreddit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($subreddit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subreddit_index');
    }
}
