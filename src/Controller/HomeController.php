<?php
namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/search', name: 'post_search')]
    public function search(PostRepository $postRepository): Response
    {
        $query = $_GET['q'] ?? '';
        $posts = $postRepository->createQueryBuilder('p')
            ->where('p.title LIKE :query OR p.content LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
