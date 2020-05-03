<?php

namespace App\Entity;

use App\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StateRepository", readOnly=true)
 */
class State
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var Collection<Task> $tasks
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="state")
     */
    private $tasks;

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
        $this->tasks = new ArrayCollection();
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
     * @return Collection
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
