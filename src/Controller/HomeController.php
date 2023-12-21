<?php

namespace App\Controller;

use App\Controller\CategoriesController;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
