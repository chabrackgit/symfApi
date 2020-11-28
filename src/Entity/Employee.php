<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeeRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 * @UniqueEntity(
 *      fields={"email"},
 *      message= "Cet email est déja utilisé"
 * )
 */
class Employee implements UserInterface, \Serializable
{

    const ROLE_ADMIN     = ['ROLE_ADMIN'];
    const ROLE_MANAGER   = ['ROLE_MANAGER'];
    const ROLE_USER      = ['ROLE_USER'];
    const DEFAULT_ROLE   = ['ROLE_USER'];


    public function __construct()
    {
        $this->roles = self::DEFAULT_ROLE;
        $this->commandes = new ArrayCollection();
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

     /**
     * @Assert\EqualTo(
     *      propertyPath="password",
     *      message = "Votre mot de passe doit être identique"
     * )
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="simple_array", length=255, nullable=true)
     */
    private $roles;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $updatedUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="employee")
     */
    private $commandes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of passwordConfirm
     */ 
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * Set the value of passwordConfirm
     *
     * @return  self
     */ 
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedUser(): ?int
    {
        return $this->createdUser;
    }

    public function setCreatedUser(int $createdUser): self
    {
        $this->createdUser = $createdUser;

        return $this;
    }

    public function getUpdatedUser(): ?int
    {
        return $this->updatedUser;
    }

    public function setUpdatedUser(int $updatedUser): self
    {
        $this->updatedUser = $updatedUser;

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
    
    /**
     * getRoles
     *
     * @return (Role|string)[] roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
    
    /**
     * Affecte les rôles de l'utilisateur
     *
     * @param  array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt()
    {
        return null; 
    }

    public function eraseCredentials()
    {
        
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setEmployee($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getEmployee() === $this) {
                $commande->setEmployee(null);
            }
        }

        return $this;
    }
}
