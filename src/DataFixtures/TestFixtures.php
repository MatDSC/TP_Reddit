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
    public const USER_REFERENCE = 'user_test';
    public const POST_REFERENCE = 'post_test';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Create test user
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setUsername('testuser');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setIsConfirmed(true);
        $manager->persist($user);
        $this->addReference(self::USER_REFERENCE, $user);

        // Create another user
        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setUsername('user2');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password123'));
        $user2->setIsConfirmed(true);
        $manager->persist($user2);

        // Create test post
        $post = new Post();
        $post->setTitle('Test Post Title');
        $post->setContent('This is a test post content.');
        $post->setAuthor($user);
        $post->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($post);
        $this->addReference(self::POST_REFERENCE, $post);

        // Create test comment
        $comment = new Comment();
        $comment->setContent('This is a test comment.');
        $comment->setPost($post);
        $comment->setAuthor($user2);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($comment);

        $manager->flush();
    }
}
