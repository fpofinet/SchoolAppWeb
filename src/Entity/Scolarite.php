<?php

namespace App\Entity;

use App\Repository\ScolariteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScolariteRepository::class)
 */
class Scolarite
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
    private $total;

    /**
     * @ORM\OneToOne(targetEntity=Eleve::class, inversedBy="scolarite", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\OneToMany(targetEntity=Tranche::class, mappedBy="scolarite")
     */
    private $tranches;

    public function __construct()
    {
        $this->tranches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * @return Collection<int, Tranche>
     */
    public function getTranches(): Collection
    {
        return $this->tranches;
    }

    public function addTranch(Tranche $tranch): self
    {
        if (!$this->tranches->contains($tranch)) {
            $this->tranches[] = $tranch;
            $tranch->setScolarite($this);
        }

        return $this;
    }

    public function removeTranch(Tranche $tranch): self
    {
        if ($this->tranches->removeElement($tranch)) {
            // set the owning side to null (unless already changed)
            if ($tranch->getScolarite() === $this) {
                $tranch->setScolarite(null);
            }
        }

        return $this;
    }
}
