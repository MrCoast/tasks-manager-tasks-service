<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Tag;
use App\Entity\Priority;
use App\Entity\State;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var Collection<Tag> $tags
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tasks", cascade={"persist"})
     */
    private $tags;

    /**
     * @var Priority $priority
     *
     * @ORM\ManyToOne(targetEntity="Priority", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $priority;

    /**
     * @var State $state
     *
     * @ORM\ManyToOne(targetEntity="State", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     * @return self
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addTask($this);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     * @return self
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeTask($this);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function clearTags(): self
    {
        $this->tags = new ArrayCollection();

        return $this;
    }

    /**
     * @return Priority|null
     */
    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    /**
     * @param Priority $priority
     * @return self
     */
    public function setPriority(Priority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return State|null
     */
    public function getState(): ?State
    {
        return $this->state;
    }

    /**
     * @param State $state
     * @return self
     */
    public function setState(State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
