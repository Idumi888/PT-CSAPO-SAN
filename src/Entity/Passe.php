<?php

namespace App\Entity;


use App\Repository\PasseRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PasseRepository::class)
 */
class Passe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Epreuve::class, inversedBy="passes")
     * @ApiSubresource()
     */
    private $epreuve;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class, inversedBy="passes")
     * @ApiSubresource()
     */
    private $eleve;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_rendu_epreuve;

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

    public function getEpreuve(): ?Epreuve
    {
        return $this->epreuve;
    }

    public function setEpreuve(?Epreuve $epreuve): self
    {
        $this->epreuve = $epreuve;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getDateRenduEpreuve(): ?\DateTimeInterface
    {
        return $this->date_rendu_epreuve;
    }

    public function setDateRenduEpreuve(?\DateTimeInterface $date_rendu_epreuve): self
    {
        $this->date_rendu_epreuve = $date_rendu_epreuve;

        return $this;
    }
}
