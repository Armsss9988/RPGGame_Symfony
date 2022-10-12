<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Paragraph;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="articles")
     */
    private $Game;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imgURLArticle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getParagraph(): ?string
    {
        return $this->Paragraph;
    }

    public function setParagraph(?string $Paragraph): self
    {
        $this->Paragraph = $Paragraph;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(?Game $Game): self
    {
        $this->Game = $Game;

        return $this;
    }

    public function getImgURLArticle(): ?string
    {
        return $this->imgURLArticle;
    }

    public function setImgURLArticle(?string $imgURLArticle): self
    {
        $this->imgURLArticle = $imgURLArticle;

        return $this;
    }
}
