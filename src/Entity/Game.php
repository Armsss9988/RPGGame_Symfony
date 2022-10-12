<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="games")
     */
    public $Category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Ram;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $DiskSpace;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $UpdateTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imgURL;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="Game")
     */
    private $articles;


    public function __construct()
    {
        $this->Category = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->Category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->Category->contains($category)) {
            $this->Category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->Category->removeElement($category);

        return $this;
    }

    public function getRam(): ?int
    {
        return $this->Ram;
    }

    public function setRam(?int $Ram): self
    {
        $this->Ram = $Ram;

        return $this;
    }

    public function getDiskSpace(): ?int
    {
        return $this->DiskSpace;
    }

    public function setDiskSpace(?int $DiskSpace): self
    {
        $this->DiskSpace = $DiskSpace;

        return $this;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->UpdateTime;
    }

    public function setUpdateTime(?\DateTimeInterface $UpdateTime): self
    {
        $this->UpdateTime = $UpdateTime;

        return $this;
    }

    public function getImgURL(): ?string
    {
        return $this->imgURL;
    }

    public function setImgURL(?string $imgURL): self
    {
        $this->imgURL = $imgURL;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setGame($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getGame() === $this) {
                $article->setGame(null);
            }
        }

        return $this;
    }

}
