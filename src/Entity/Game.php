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
     * @ORM\OneToMany(targetEntity=Link::class, mappedBy="Game")
     */
    private $links;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Article;

    /**
     * @ORM\OneToOne(targetEntity=Article::class, mappedBy="Game", cascade={"persist", "remove"})
     */
    private $article;




    public function __construct()
    {
        $this->Category = new ArrayCollection();
        $this->links = new ArrayCollection();
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

    /**
     * @return Collection<int, Link>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Link $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setGame($this);
        }

        return $this;
    }

    public function removeLink(Link $link): self
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getGame() === $this) {
                $link->setGame(null);
            }
        }

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        // unset the owning side of the relation if necessary
        if ($article === null && $this->article !== null) {
            $this->article->setGame(null);
        }

        // set the owning side of the relation if necessary
        if ($article !== null && $article->getGame() !== $this) {
            $article->setGame($this);
        }

        $this->article = $article;

        return $this;
    }
}
