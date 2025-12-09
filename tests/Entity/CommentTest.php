<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testCommentEntity(): void
    {
        $comment = new Comment();
        $post = new Post();
        $user = new User();

        $comment->setContent('Commentaire incroyable');
        $comment->setPost($post);
        $comment->setAuthor($user);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $this->assertEquals('Commentaire incroyable', $comment->getContent());
        $this->assertSame($post, $comment->getPost());
        $this->assertSame($user, $comment->getAuthor());
        $this->assertInstanceOf(\DateTime::class, $comment->getCreatedAt());
    }
}
