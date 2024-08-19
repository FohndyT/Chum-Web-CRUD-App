<?php

namespace App\Controller;

use App\BaseData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InitializeDataController extends AbstractController
{
    #[Route(path: '/initialize-data', name: 'initialize_data')]
    public function initializeData(BaseData $baseData, EntityManagerInterface $entityManager): Response
    {
        $baseData->load($entityManager);

        return $this->render('initialize.html.twig');
    }
}