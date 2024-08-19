<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InfoForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ManageController extends AbstractController
{
    #[Route('/manage', name: 'manage_route')]
    public function account(): Response
    {
        return $this->render('manage.html.twig');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/accounts-view', name: 'accountsView_route')]
    public function accountsView(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $userCategory = [
            'ROLE_USER' => [],
            'ROLE_ADMIN' => []
        ];

        foreach ($users as $user)
        {
            if (in_array('ROLE_ADMIN', $user->getRoles()))
            {
                $userCategory['ROLE_ADMIN'][] = $user;
            }
            elseif (in_array('ROLE_USER', $user->getRoles()))
            {
                $userCategory['ROLE_USER'][] = $user;
            }
        }

        return $this->render('manage/manageAccounts.html.twig', [
            'userCategory' => $userCategory,
        ]);
    }

    #[Route('/manage/delete-my-account', name: 'deleteMyAccount_route')]
    public function accountDelete(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof UserInterface)
        {
            throw $this->createAccessDeniedException('You must be logged in to delete your account.');
        }

        return $this->render('manage/deleteMyAccount.html.twig');
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/manage/delete-my-account/deleted', name: 'myAccountDeleted_route')]
    public function deleteMyAccount(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof UserInterface)
        {
            throw $this->createAccessDeniedException('You must be logged in to delete your account.');
        }

        $this->container->get('security.token_storage')->setToken(null);

        $entityManager->remove($user);
        $entityManager->flush();

        $request->getSession()->invalidate();

        return $this->redirectToRoute('homepage_route');
    }

    #[Route('/manage/modify-my-account', name: 'modifyMyAccount_route')]
    public function modifyMyAccount(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        if (!$user instanceof UserInterface)
        {
            throw $this->createAccessDeniedException('You must be logged in to modify your account information.');
        }

        $user = $this->getUser();
        $form = $this->createForm(InfoForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // If the password field is not empty, hash the new password and set it
            $plainPassword = $form->get('plainPassword')->getData();
            if (!empty($plainPassword))
            {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $plainPassword
                    )
                );
            }

            $entityManager->flush();

            return $this->redirectToRoute('modifyMyAccount_route');
        }

        return $this->render('manage/modifyMyAccount.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manage/accounts-view/account/{id}', name: 'accountDetails_route')]
    public function accountDetails(User $user): Response
    {
        return $this->render('manage/AccountDetails.html.twig', [
            'user' => $user,
        ]);
    }
}


