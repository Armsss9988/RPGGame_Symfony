<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $Description;

    /**
     * @ORM\OneToMany(targetEntity=GameCategory::class, mappedBy="Category", orphanRemoval=true)
     */
    private $gameCategories;



    public function __construct()
    {
        $this->gameTypes = new ArrayCollection();
        $this->gameCategories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, GameCategory>
     */
    public function getGameCategories(): Collection
    {
        return $this->gameCategories;
    }

    public function addGameCategory(GameCategory $gameCategory): self
    {
        if (!$this->gameCategories->contains($gameCategory)) {
            $this->gameCategories[] = $gameCategory;
            $gameCategory->setCategory($this);
        }

        return $this;
    }

    public function removeGameCategory(GameCategory $gameCategory): self
    {
        if ($this->gameCategories->removeElement($gameCategory)) {
            // set the owning side to null (unless already changed)
            if ($gameCategory->getCategory() === $this) {
                $gameCategory->setCategory(null);
            }
        }

        return $this;
    }
}
