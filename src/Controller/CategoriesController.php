<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Form\CategorieType;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/{id}', name: 'app_categories_show')]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categories/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    //action pour ajouter une categorie
    #[Route('/categories/ajouter', name: 'app_categories_ajouter')]
    public function ajouter(Request $request, ManagerRegistry $doctrine): Response
    {
        //creation du formulaire
        //on cree un objet de la classe Categorie
        $categorie = new Categorie();
        //on cree le formulaire
        $form = $this->createForm(CategorieType::class, $categorie);

        //todo : traiter le formulaire en retour
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            //on redirige vers l'index
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categories/ajouter.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/categories/modifier/{id}', name: 'app_categories_modifier')]
    public function modifier($id, Request $request, ManagerRegistry $doctrine): Response
    {
        //creation du formulaire
        //on cree un objet de la classe Categories
        $repo = $doctrine->getRepository(Categorie::class);
        $categorie = $repo->find($id);
        //on cree le formulaire
        $form = $this->createForm(CategorieType::class, $categorie);

        //todo : traiter le formulaire en retour
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            //on redirige vers l'index
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categories/modifier.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }
}
