<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\AssignUserToProjectForm;
use App\Form\ProjectForm;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProjectController extends AbstractController
{
    #[Route('/manage/projects', name: 'projects')]
    public function projects(ProjectRepository $projectRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $projects = $projectRepository->findAll();
        }
        else
        {
            $projects = $user->getProjects();
        }

        return $this->render('project/project.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/projects/create-project', name: 'create_project')]
    public function createProject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('projects');
        }

        return $this->render('project/createProject.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/projects/assign', name: 'assign_user_to_project')]
    public function assignUserToProject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssignUserToProjectForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $project = $data['projectCode'];
            $user = $data['user'];

            $project->addManager($user);
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('projects');
        }

        return $this->render('project/assignUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project/{id}', name: 'project_detail')]
    public function detail(Project $project): Response
    {
        return $this->render('project/projectDetails.html.twig', [
            'project' => $project,
        ]);
    }
}
