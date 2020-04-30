<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Task;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriorityRepository", readOnly=true)
 */
class Priority
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
     * @var Task[] $tasks
     * 
     * @ORM\OneToMany(targetEntity="Task", mappedBy="priority")
     */
    private $tasks;

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
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
}
