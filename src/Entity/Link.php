<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinkRepository::class)
 */
class Link
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="links")
     */
    private $Game;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $LinkURL;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLinkURL(): ?string
    {
        return $this->LinkURL;
    }

    public function setLinkURL(?string $LinkURL): self
    {
        $this->LinkURL = $LinkURL;

        return $this;
    }
}
