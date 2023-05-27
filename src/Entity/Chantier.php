<?php

namespace App\Entity;

use App\Repository\ChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierRepository::class)]
class Chantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_chantier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    #[ORM\ManyToMany(targetEntity: Interlocuteur::class, mappedBy: 'Chantier')]
    private Collection $interlocuteurs;

    #[ORM\OneToMany(mappedBy: 'Chantier', targetEntity: Historique::class)]
    private Collection $historiques;

    public function __construct()
    {
        $this->interlocuteurs = new ArrayCollection();
        $this->historiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdChantier(): ?int
    {
        return $this->id_chantier;
    }

    public function setIdChantier(?int $id_chantier): self
    {
        $this->id_chantier = $id_chantier;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Interlocuteur>
     */
    public function getInterlocuteurs(): Collection
    {
        return $this->interlocuteurs;
    }

    public function addInterlocuteur(Interlocuteur $interlocuteur): self
    {
        if (!$this->interlocuteurs->contains($interlocuteur)) {
            $this->interlocuteurs->add($interlocuteur);
            $interlocuteur->addChantier($this);
        }

        return $this;
    }

    public function removeInterlocuteur(Interlocuteur $interlocuteur): self
    {
        if ($this->interlocuteurs->removeElement($interlocuteur)) {
            $interlocuteur->removeChantier($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): self
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setChantier($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): self
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getChantier() === $this) {
                $historique->setChantier(null);
            }
        }

        return $this;
    }
}
