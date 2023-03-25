<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $devoir;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rattrapage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getEleve(): ?User
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }


    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getDevoir(): ?float
    {
        return $this->devoir;
    }

    public function setDevoir(?float $devoir): self
    {
        $this->devoir = $devoir;

        return $this;
    }

    public function getRattrapage(): ?float
    {
        return $this->rattrapage;
    }

    public function setRattrapage(?float $rattrapage): self
    {
        $this->rattrapage = $rattrapage;

        return $this;
    }
}
