<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

    /**
     * @ORM\Entity(repositoryClass=UsersRepository::class)
     * @UniqueEntity(
     * fields={"email"},
     * message="L'email que vous avez renseigné est déjà utilisé !"
     * )
     */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password", message="Vous n'avez pas entré le même mot de passe")
     */
    private $password;
     /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas entré le même mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="utilisateur", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="utilisateurs", orphanRemoval=true)
     */
    private $Postarticles;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="userblog")
     */
    private $userRoles;
    //relation de cet utilisateur avec les différents rôles dans la BDD



    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->Postarticles = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }


       //Ajouté dans l'entité pour convertir en chaîne de caractères
     /**
     * Transform to string
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getUsername();
        return (string) $this->getUserRoles();


    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    
    public function eraseCredentials(){}

    public function getSalt(){}

    public function getRoles(){
        //Pris en compte des rôles utilisateurs au sein de la BDD

        // $roles = $this->userRoles->toArray();

        // dump($roles);

        $roles = $this->userRoles->map(function($role){
            return $role->getIntitule();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
        
        //cette fonction retourne par défaut un tableau contenant "Role_User". Par défaut, tout utilisateur aura donc un role de USER
        // return ['ROLE_USER'];
        
    }

    /**
     * @return Collection|Comment[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Comment $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Comment $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getUtilisateur() === $this) {
                $commentaire->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getPostarticles(): Collection
    {
        return $this->Postarticles;
    }

    public function addPostarticle(Article $postarticle): self
    {
        if (!$this->Postarticles->contains($postarticle)) {
            $this->Postarticles[] = $postarticle;
            $postarticle->setUtilisateurs($this);
        }

        return $this;
    }

    public function removePostarticle(Article $postarticle): self
    {
        if ($this->Postarticles->contains($postarticle)) {
            $this->Postarticles->removeElement($postarticle);
            // set the owning side to null (unless already changed)
            if ($postarticle->getUtilisateurs() === $this) {
                $postarticle->setUtilisateurs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUserblog($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUserblog($this);
        }

        return $this;
    }


}
