<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Src\Controller\ArticleController;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
    
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé avec l\'ID : '.$id);
        }
    
        $commentaire = new Commentaire();
        $commentaire->setArticle($article);
    
        // Création du formulaire
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté
            $utilisateur = $this->getUser();
    
            // Si l'utilisateur est connecté
            if ($utilisateur !== null) {
                // Associer l'utilisateur au commentaire
                $commentaire->setAuteur($utilisateur);
    
                // Sauvegarder le commentaire
                $entityManager->persist($commentaire);
                $entityManager->flush();
    
                // Rediriger l'utilisateur vers la page de l'article après la création du commentaire
                return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
            }
    
            // Si quelque chose ne s'est pas bien passé, afficher un message d'erreur
            $this->addFlash('error', 'Une erreur s\'est produite lors de la création du commentaire.');
        }
    
        return $this->render('commentaire/new.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }
    
    

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CommentaireType::class, $commentaire);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('commentaire/edit.html.twig', [
    //         'commentaire' => $commentaire,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
