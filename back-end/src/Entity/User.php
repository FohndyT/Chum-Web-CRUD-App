<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an manage with this email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $firstName = " ";

    #[ORM\Column]
    private ?string $lastName = " ";

    #[ORM\ManyToMany(targetEntity: 'App\Entity\Profile', inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_profile_relation')]
    private Collection $profiles;

    #[ORM\ManyToMany(targetEntity: 'App\Entity\Project', mappedBy: 'managers')]
    private Collection $projects;

    private array $roles = ['ROLE_USER'];

    public function __construct()
    {
        $this->profiles = new ArrayCollection();
    }

    #[ORM\Column]
    private ?string $password = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterfac
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


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


    public function hasRole(string $roleName): bool
    {
        foreach ($this->getProfiles() as $profile)
        {
            foreach ($profile->getRoles() as $role)
            {
                if ($role->getName() === $roleName)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        foreach ($this->getProfiles() as $profile)
        {
            foreach ($profile->getRoles() as $role)
            {
                $roles[] = $role->getName();  // Add the role names
            }
        }

        return array_unique($roles);
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }
}
