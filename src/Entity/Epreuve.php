<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EpreuveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEpreuve;

    /**
     * @ORM\OneToMany(targetEntity=Passe::class, mappedBy="epreuve")
     */
    private $passes;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="epreuves")
     */
    private $utilisateurs;

    public function __construct()
    {
        $this->passes = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }

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

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
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

    /**
     * @return Collection|Passe[]
     */
    public function getPasses(): Collection
    {
        return $this->passes;
    }

    public function addPass(Passe $pass): self
    {
        if (!$this->passes->contains($pass)) {
            $this->passes[] = $pass;
            $pass->setEpreuve($this);
        }

        return $this;
    }

    public function removePass(Passe $pass): self
    {
        if ($this->passes->removeElement($pass)) {
            // set the owning side to null (unless already changed)
            if ($pass->getEpreuve() === $this) {
                $pass->setEpreuve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateurs->removeElement($utilisateur);

        return $this;
    }
}
