<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;

class AcceuilController extends AbstractController
{
    #[Route('/acceuil', name: 'app_acceuil')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        if ($this->getUser()) {
            $this->denyAccessUnlessGranted('ROLE_USER');
        }

        return $this->render('acceuil/index.html.twig', [
            'controller_name' => 'AcceuilController',
            'categories' => $categorieRepository->findAll(),
        ]);
    }
}
