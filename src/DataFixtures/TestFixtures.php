<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{
    public const USER_REFERENCE = 'test_unitaire';
    public const USER2_REFERENCE = 'test_unitaire2';
    public const POST_REFERENCE = 'test_post';
    public const COMMENT_REFERENCE = 'test_comment';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {

        $user1 = new User();
        $user1->setEmail('test@example.com');
        $user1->setUsername('test');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'motdepassetest'));
        $user1->setIsConfirmed(true);
        $manager->persist($user1);
        $this->addReference(self::USER_REFERENCE, $user1);

        $user2 = new User();
        $user2->setEmail('test2@example.com');
        $user2->setUsername('test2');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'motdepassetest2'));
        $user2->setIsConfirmed(true);
        $manager->persist($user2);
        $this->addReference(self::USER2_REFERENCE, $user2);

        $user3 = new User();
        $user3->setEmail('noconfirmed@example.com');
        $user3->setUsername('noconfirmed');
        $user3->setPassword($this->passwordHasher->hashPassword($user3, 'motdepassenc'));
        $user3->setIsConfirmed(false);
        $user3->setActivationToken('token_001');
        $user3->setTokenExpiresAt(new \DateTime('+24 hours'));
        $manager->persist($user3);

        for ($i = 1; $i <= 3; $i++) {
            $post = new Post();
            $post->setTitle("Titre $i");
            $post->setContent("Contenu n° $i.");
            $post->setAuthor($user1);
            $post->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($post);

            if ($i === 1) {
                $this->addReference(self::POST_REFERENCE, $post);
            }
        }

        $comment = new Comment();
        $comment->setContent('Commentaire test.');
        $comment->setPost($this->getReference(self::POST_REFERENCE));
        $comment->setAuthor($user2);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($comment);
        $this->addReference(self::COMMENT_REFERENCE, $comment);

        $manager->flush();
    }
}
