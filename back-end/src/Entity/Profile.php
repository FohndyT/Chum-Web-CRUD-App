<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: 'App\Repository\ProfileRepository')]
#[ORM\Table(name: "profile")]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_ROLE_PROFILE', fields: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'This profile already exists!')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private string $name;

    #[ORM\Column(type:"string", length:255)]
    private string $description = "";

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'profiles')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: 'App\Entity\Role', inversedBy: 'profiles')]
    #[ORM\JoinTable(name: 'profile_role')]
    private Collection $roles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // Name Methods
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    // Description method
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    // Users Method
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Profile $user): self
    {
        if (!($this->users->contains($user)))
        {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(Profile $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    // Roles Method
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!($this->roles->contains($role)))
        {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Profile $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }
}