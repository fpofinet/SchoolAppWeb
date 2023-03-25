<?php

namespace App\Entity;

use App\Repository\FiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FiliereRepository::class)
 */
class Filiere
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
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToOne(targetEntity=Salle::class, cascade={"persist", "remove"})
     */
    private $salle;

    /**
     * @ORM\Column(type="integer")
     */
    private $effectif;

    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, mappedBy="filiere")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="filiere")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Eleve::class, mappedBy="filiere")
     */
    private $eleves;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->eleves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getEffectif(): ?int
    {
        return $this->effectif;
    }

    public function setEffectif(int $effectif): self
    {
        $this->effectif = $effectif;

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->addFiliere($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            $matiere->removeFiliere($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Eleve $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setFiliere($this);
        }

        return $this;
    }

    public function removeUser(Eleve $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getFiliere() === $this) {
                $user->setFiliere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Eleve>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setFiliere($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getFiliere() === $this) {
                $elefe->setFiliere(null);
            }
        }

        return $this;
    }
}
