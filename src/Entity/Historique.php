<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_h = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    private ?Interlocuteur $interlocuteur = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    private ?Chantier $Chantier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdH(): ?int
    {
        return $this->id_h;
    }

    public function setIdH(?int $id_h): self
    {
        $this->id_h = $id_h;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getInterlocuteur(): ?Interlocuteur
    {
        return $this->interlocuteur;
    }

    public function setInterlocuteur(?Interlocuteur $interlocuteur): self
    {
        $this->interlocuteur = $interlocuteur;

        return $this;
    }

    public function getChantier(): ?Chantier
    {
        return $this->Chantier;
    }

    public function setChantier(?Chantier $Chantier): self
    {
        $this->Chantier = $Chantier;

        return $this;
    }
}
