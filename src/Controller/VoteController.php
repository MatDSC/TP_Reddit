<?php
namespace App\Controller;

use App\Entity\Vote;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vote')]
class VoteController extends AbstractController
{
    #[Route('/{type}/{entity}/{id}', name: 'vote_toggle', methods: ['POST'])]
    public function toggle(Request $request, string $type, string $entity, int $id, VoteRepository $voteRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // Find the target (post or comment)
        $target = $entityManager->getRepository($entity === 'post' ? \App\Entity\Post::class : \App\Entity\Comment::class)->find($id);
        if (!$target) {
            return new JsonResponse(['error' => 'Target not found'], 404);
        }

        // Check for existing vote
        $existingVote = $voteRepository->findOneBy(['user' => $user, $entity => $target]);
        if ($existingVote) {
            if ($existingVote->getType() === $type) {
                // Remove vote if same type
                $entityManager->remove($existingVote);
            } else {
                // Change vote type
                $existingVote->setType($type);
            }
        } else {
            // Create new vote
            $vote = new Vote();
            $vote->setUser($user);
            $vote->setType($type);
            if ($entity === 'post') {
                $vote->setPost($target);
            } else {
                $vote->setComment($target);
            }
            $entityManager->persist($vote);
        }
        $entityManager->flush();

        // Return updated counts (compute dynamically)
        $upvotes = $voteRepository->count(['type' => 'up', $entity => $target]);
        $downvotes = $voteRepository->count(['type' => 'down', $entity => $target]);

        return new JsonResponse(['upvotes' => $upvotes, 'downvotes' => $downvotes]);
    }
}
