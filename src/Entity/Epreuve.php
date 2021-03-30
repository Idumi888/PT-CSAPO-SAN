<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EpreuveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EpreuveRepository::class)
 */
class Epreuve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeModule;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nomModule;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $classe;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreEleve;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $sujet;

    /**
     * @ORM\Column(type="datetime")
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEpreuve;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeModule(): ?int
    {
        return $this->codeModule;
    }

    public function setCodeModule(int $codeModule): self
    {
        $this->codeModule = $codeModule;

        return $this;
    }

    public function getNomModule(): ?string
    {
        return $this->nomModule;
    }

    public function setNomModule(string $nomModule): self
    {
        $this->nomModule = $nomModule;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getNombreEleve(): ?int
    {
        return $this->nombreEleve;
    }

    public function setNombreEleve(int $nombreEleve): self
    {
        $this->nombreEleve = $nombreEleve;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateEpreuve(): ?\DateTimeInterface
    {
        return $this->dateEpreuve;
    }

    public function setDateEpreuve(\DateTimeInterface $dateEpreuve): self
    {
        $this->dateEpreuve = $dateEpreuve;

        return $this;
    }
}
