<?php

namespace App\Entity;

use App\Repository\ChatonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatonRepository::class)]
class Chaton
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Photo = null;

    #[ORM\ManyToOne(inversedBy: 'chatons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $Categorie = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $nb_like = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo): static
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): static
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getNbLike(): ?int
    {
        return $this->nb_like;
    }

    public function setNbLike(int $nb_like): static
    {
        $this->nb_like = $nb_like;

        return $this;
    }
}
