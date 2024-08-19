<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'project')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PROJECT_CODE', fields: ['projectCode'])]
#[UniqueEntity(fields: ['projectCode'], message: 'There is already a project with this code.')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $projectCode = null;

    #[ORM\Column]
    private ?string $projectName = null;

    #[ORM\Column]
    private ?string $projectDateDebut = null;

    #[ORM\Column]
    private ?string $projectDateEnd = null;

    #[ORM\Column]
    private ?string $projectDescription = null;

    #[ORM\ManyToMany(targetEntity: 'App\Entity\User', inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_manager_relation')]
    private Collection $managers;


    public function getProjectCode(): ?string
    {
        return $this->projectCode;
    }

    public function setProjectCode(string $projectCode): self
    {
        $this->projectCode = $projectCode;
        return $this;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): self
    {
        $this->projectName = $projectName;
        return $this;
    }

    public function getProjectDateDebut(): ?string
    {
        return $this->projectDateDebut;
    }

    public function setProjectDateDebut(string $projectDateDebut): self
    {
        $this->projectDateDebut = $projectDateDebut;
        return $this;
    }

    public function getProjectDateEnd(): ?string
    {
        return $this->projectDateEnd;
    }

    public function setProjectDateEnd(string $projectDateEnd): self
    {
        $this->projectDateEnd = $projectDateEnd;
        return $this;
    }

    public function getProjectDescription(): ?string
    {
        return $this->projectDescription;
    }

    public function setProjectDescription(string $projectDescription): self
    {
        $this->projectDescription = $projectDescription;
        return $this;
    }

    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(User $manager): self
    {
        if (!($this->managers->contains($manager)))
        {
            $this->managers->add($manager);
        }

        return $this;
    }

    public function removeManager(User $manager): self
    {
        if ($this->managers->contains($manager))
        {
            $this->managers->removeElement($manager);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
