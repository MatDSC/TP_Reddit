<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testPostEntity(): void
    {
        $post = new Post();
        $user = new User();

        $post->setTitle('Titre incroyable');
        $post->setContent('Contenu incroyable');
        $post->setAuthor($user);

        $this->assertEquals('Titre incroyable', $post->getTitle());
        $this->assertEquals('Contenu incroyable', $post->getContent());
        $this->assertSame($user, $post->getAuthor());
        $this->assertNotNull($post->getid());
        $this->assertInstanceOf(\DateTime::class, $post->getCreatedAt());
    }

    public function testPostUidGeneration(): void
    {
        $post1 = new Post();
        $post2 = new Post();

        $this->assertNotNull($post1->getid());
        $this->assertNotNull($post2->getid());
        $this->assertNotEquals($post1->getid(), $post2->getid());
    }

    public function testPostComments(): void
    {
        $post = new Post();
        $comment = new Comment();

        $this->assertCount(0, $post->getComments());

        $post->addComment($comment);
        $this->assertCount(1, $post->getComments());
        $this->assertTrue($post->getComments()->contains($comment));

        $post->removeComment($comment);
        $this->assertCount(0, $post->getComments());
    }
}
