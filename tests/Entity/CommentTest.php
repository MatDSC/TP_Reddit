<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private Comment $comment;

    protected function setUp(): void
    {
        $this->comment = new Comment();
    }

    public function testCommentEntity(): void
    {
        $post = new Post();
        $user = new User();
        $createdAt = new \DateTimeImmutable();

        $this->comment->setContent('Test comment content');
        $this->comment->setPost($post);
        $this->comment->setAuthor($user);
        $this->comment->setCreatedAt($createdAt);

        $this->assertEquals('Test comment content', $this->comment->getContent());
        $this->assertSame($post, $this->comment->getPost());
        $this->assertSame($user, $this->comment->getAuthor());

        // Accepter DateTime ou DateTimeImmutable
        $this->assertInstanceOf(\DateTimeInterface::class, $this->comment->getCreatedAt());
    }

    public function testCommentId(): void
    {
        $this->assertNull($this->comment->getId());
    }

    public function testCommentRelationships(): void
    {
        $post = new Post();
        $user = new User();

        $this->comment->setPost($post);
        $this->comment->setAuthor($user);

        $this->assertSame($post, $this->comment->getPost());
        $this->assertSame($user, $this->comment->getAuthor());
    }

    public function testCommentContent(): void
    {
        $content = 'This is a test comment with some content.';
        $this->comment->setContent($content);

        $this->assertEquals($content, $this->comment->getContent());
    }

    public function testCommentCreatedAtAutoSet(): void
    {
        $comment = new Comment();
        $comment->setCreatedAt(new \DateTimeImmutable());

        $this->assertInstanceOf(\DateTimeInterface::class, $comment->getCreatedAt());
    }
}

