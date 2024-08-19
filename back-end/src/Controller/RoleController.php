<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleForm;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RoleController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/manage-roles', name: 'manage_roles',)]
    public function manageRoles(RoleRepository $roleRepository): Response
    {
        $roles = $roleRepository->findAll();
        return $this->render('role/manageRoles.html.twig', ['roles' => $roles]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/manage-roles/create-role', name: 'createRole',)]
    public function createRole(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $role = new Role();
        $form = $this->createForm(RoleForm::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($role);
            $entityManager->flush();

            return $this->redirectToRoute('manage_roles');
        }

        return $this->render('role/createRole.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/role/{id}', name: 'role_detail')]
    public function detail(Role $role): Response
    {
        return $this->render('role/roleDetails.html.twig', [
            'role' => $role,
        ]);
    }
}