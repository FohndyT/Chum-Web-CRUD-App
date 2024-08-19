<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: 'App\Repository\RoleRepository')]
#[ORM\Table(name: 'role')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_ROLE_NAME', fields: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'This role already exists!')]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private string $name;

    #[ORM\Column(type:"string", length:255)]
    private string $description = "";

    #[ORM\ManyToMany(targetEntity:"Profile", mappedBy:"roles")]
    private Collection $profiles;

    public function getId(): ?int
    {
        return $this->id;
    }

    // Name Method
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    // Description Method
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    // Profiles Method
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(Profile $profile): self
    {
        if (!($this->profiles->contains($profile)))
        {
            $this->profiles->add($profile);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        $this->profiles->removeElement($profile);

        return $this;
    }
}

