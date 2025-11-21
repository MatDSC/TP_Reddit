<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'vote', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'unique_user_post_vote', columns: ['user_id', 'post_id']),
    new ORM\UniqueConstraint(name: 'unique_user_comment_vote', columns: ['user_id', 'comment_id'])
])]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    private ?Post $post = null;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    private ?Comment $comment = null;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $type = null; // 'up' or 'down'

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, ['up', 'down'])) {
            throw new \InvalidArgumentException('Vote type must be "up" or "down".');
        }
        $this->type = $type;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Helper method to get the target (post or comment)
    public function getTarget(): Post|Comment|null
    {
        return $this->post ?? $this->comment;
    }
}
