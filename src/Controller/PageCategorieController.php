<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CategorieController;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;
use App\Repository\ArticleRepository;
use App\Entity\Article;




class PageCategorieController extends AbstractController
{
    #[Route('/page/categorie', name: 'app_page_categorie')]
    public function show2(Categorie $categorie, EntityManagerInterface $em, CategorieRepository $categorieRepository): Response
    {
        $articles = $categorie->getArticles();
        
        return $this->render('page_categorie/index.html.twig', [
            'controller_name' => 'PageCategorieController',
            'categories' => $categorieRepository->findAll(),
            'articles' => $articles,
        ]);
    }
}

