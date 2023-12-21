<?php

namespace App\Controller;

use App\Entity\Chaton;
use App\Form\ChatonType;
use App\Repository\ChatonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chatons')]
class ChatonsController extends AbstractController
{
    #[Route('/', name: 'app_chatons_index', methods: ['GET'])]
    public function index(ChatonRepository $chatonRepository): Response
    {
        return $this->render('chatons/index.html.twig', [
            'chatons' => $chatonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chatons_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chaton = new Chaton();
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('Photo')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );

            $chaton->setPhoto($fileName);

            $entityManager->persist($chaton);
            $entityManager->flush();

            return $this->redirectToRoute('app_chatons_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chatons/new.html.twig', [
            'chaton' => $chaton,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chatons_show', methods: ['GET'])]
    public function show(Chaton $chaton): Response
    {
        return $this->render('chatons/show.html.twig', [
            'chaton' => $chaton,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chatons_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chaton $chaton, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chatons_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chatons/edit.html.twig', [
            'chaton' => $chaton,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chatons_delete', methods: ['POST'])]
    public function delete(Request $request, Chaton $chaton, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chaton->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chaton);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chatons_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/like/{id}', name: 'app_chatons_like', methods: ['GET'])]
    public function like($id, ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Chaton::class);
        $chaton = $repo->find($id);

        $chaton->setNbLike($chaton->getNbLike() + 1);
        $em = $doctrine->getManager();
        $em->persist($chaton);
        $em->flush();

        return $this->json(['nb_like' => $chaton->getNbLike()]
        );
    }

    #[Route('/unlike/{id}', name: 'app_chatons_unlike', methods: ['GET'])]
    public function unlike($id, ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Chaton::class);
        $chaton = $repo->find($id);

        // Vérifier si le nombre de likes est supérieur à zéro avant de décrémenter
        if ($chaton->getNbLike() > 0) {
            $chaton->setNbLike($chaton->getNbLike() - 1);
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();
        }

        return $this->json(['nb_like' => $chaton->getNbLike()]);
    }
}
