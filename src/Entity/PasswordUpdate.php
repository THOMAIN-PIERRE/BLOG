<?php

namespace App\Entity;

use App\Repository\PasswordUpdateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
   
    private $oldPassword;
    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit contenir au moins 8 caractères et doit comporter au moins une minuscule, une majuscule, un chiffre et un caractère spécial")
     * 
     */
    private $newPassword;
    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Oups, la confirmation du mot de passe est erronée !")
     */
    private $confirmPassword;


    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
