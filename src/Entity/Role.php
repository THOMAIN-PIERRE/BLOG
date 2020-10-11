<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $intitule;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, inversedBy="userRoles")
     */
    private $userblog;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $definition;

    public function __construct()
    {
        $this->userblog = new ArrayCollection();
    }

    // //Ajouté pour convertir les roles en chaîne de caractères
    //  /**
    //  * Transform to string
    //  * 
    //  * @return string
    //  */
    // public function __toString()
    // {
    //     return (string) $this->getUserblog();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUserblog(): Collection
    {
        return $this->userblog;
    }

    public function addUserblog(Users $userblog): self
    {
        if (!$this->userblog->contains($userblog)) {
            $this->userblog[] = $userblog;
        }

        return $this;
    }

    public function removeUserblog(Users $userblog): self
    {
        if ($this->userblog->contains($userblog)) {
            $this->userblog->removeElement($userblog);
        }

        return $this;
    }

    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    public function setDefinition(?string $definition): self
    {
        $this->definition = $definition;

        return $this;
    }
}
