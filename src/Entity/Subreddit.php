<?php
namespace App\Entity;

use App\Repository\SubredditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubredditRepository::class)]
class Subreddit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'text')]
    private ?string $rules = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $moderators;

    public function __construct() { $this->moderators = new ArrayCollection(); }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }
    public function getRules(): ?string { return $this->rules; }
    public function setRules(string $rules): self { $this->rules = $rules; return $this; }
    public function getCreatedBy(): ?User { return $this->createdBy; }
    public function setCreatedBy(?User $createdBy): self { $this->createdBy = $createdBy; return $this; }
    public function getModerators(): Collection { return $this->moderators; }
    public function addModerator(User $moderator): self { if (!$this->moderators->contains($moderator)) { $this->moderators->add($moderator); } return $this; }
    public function removeModerator(User $moderator): self { $this->moderators->removeElement($moderator); return $this; }
}
