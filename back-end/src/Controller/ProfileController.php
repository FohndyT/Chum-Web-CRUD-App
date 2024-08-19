<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\AssignProfileToUserForm;
use App\Form\AssignRoleToProfileForm;
use App\Form\ProfileForm;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/manage-profiles', name: 'manage_profiles')]
    public function manageProfiles(ProfileRepository $profileRepository): Response
    {
        $profiles = $profileRepository->findAll();
        return $this->render('profile/manageProfiles.html.twig', ['profiles' => $profiles]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/manage-profiles/create-profile', name: 'createProfile.html.twig')]
    public function createRole(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $profile = new Profile();
        $form = $this->createForm(ProfileForm::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('manage_profiles');
        }

        return $this->render('profile/createProfile.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/profile/{id}', name: 'profile_detail')]
    public function detail(Profile $profile): Response
    {
        return $this->render('profile/profileDetails.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/manage-profiles/assign', name: 'assign_role_to_profile')]
    public function assignRoleToProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssignRoleToProfileForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $profile = $data['profile'];
            $role = $data['role'];

            $profile->addRole($role);
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('manage_profiles');
        }

        return $this->render('profile/assignRoleToProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/manage-profiles/assign-profile', name: 'assign_profile_to_user')]
    public function assignProfileToUserForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssignProfileToUserForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $data['user'];
            $profile = $data['profile'];

            $user->addProfile($profile);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('manage_profiles');
        }

        return $this->render('profile/assignProfileToUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}