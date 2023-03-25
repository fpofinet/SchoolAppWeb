<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EleveRepository::class)
 */
class Eleve extends User
{

    /**
     * @ORM\ManyToOne(targetEntity=Filiere::class, inversedBy="eleves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $filiere;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="eleves")
     */
    private $notes;

    /**
     * @ORM\OneToOne(targetEntity=Scolarite::class, mappedBy="eleve", cascade={"persist", "remove"})
     */
    private $scolarite;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setEleve($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEleve() === $this) {
                $note->setEleve(null);
            }
        }

        return $this;
    }

    public function getScolarite(): ?Scolarite
    {
        return $this->scolarite;
    }

    public function setScolarite(Scolarite $scolarite): self
    {
        // set the owning side of the relation if necessary
        if ($scolarite->getEleve() !== $this) {
            $scolarite->setEleve($this);
        }

        $this->scolarite = $scolarite;

        return $this;
    }
}
