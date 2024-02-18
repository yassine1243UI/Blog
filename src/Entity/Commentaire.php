<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[Broadcast]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Commentaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_publication;

    #[ORM\Column]
    private ?bool $Etat = true;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Auteur = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $Article = null;


    public function __construct()
    {
        $this->Date_publication = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(?string $Commentaire): static
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->Date_publication;
    }

    public function setDatePublication(\DateTimeInterface $Date_publication): static
    {
        if ($this->Date_publication === null) {
            $this->Date_publication = new \DateTime();
        }
        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->Etat;
    }

    public function setEtat(bool $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->Auteur;
    }

    public function setAuteur(?User $Auteur): static
    {
        $this->Auteur = $Auteur;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->Article;
    }

    public function setArticle(?Article $Article): static
    {
        $this->Article = $Article;

        return $this;
    }
}
