<?php

namespace App\Entity;

use App\Repository\InterlocuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterlocuteurRepository::class)]
class Interlocuteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_inter = null;

    #[ORM\Column(length: 255, nullable: true,unique: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true,unique: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\ManyToMany(targetEntity: Chantier::class, inversedBy: 'interlocuteurs')]
    private Collection $Chantier;

    #[ORM\OneToMany(mappedBy: 'interlocuteur', targetEntity: Historique::class)]
    private Collection $historiques;

    public function __construct()
    {
        $this->Chantier = new ArrayCollection();
        $this->historiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdInter(): ?int
    {
        return $this->id_inter;
    }

    public function setIdInter(?int $id_inter): self
    {
        $this->id_inter = $id_inter;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Chantier>
     */
    public function getChantier(): Collection
    {
        return $this->Chantier;
    }

    public function addChantier(Chantier $chantier): self
    {
        if (!$this->Chantier->contains($chantier)) {
            $this->Chantier->add($chantier);
        }

        return $this;
    }

    public function removeChantier(Chantier $chantier): self
    {
        $this->Chantier->removeElement($chantier);

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
            $historique->setInterlocuteur($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): self
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getInterlocuteur() === $this) {
                $historique->setInterlocuteur(null);
            }
        }

        return $this;
    }
}
