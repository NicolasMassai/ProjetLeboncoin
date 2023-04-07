<?php

namespace App\Controller\User_Online;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user/annonce', name: 'app_annonce')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $annonce = $annonceRepository->findAll();

        return $this->render('annonce/user/index.html.twig', [
            'annonces' => $annonce,
        ]);
    }

    #[Route('/user/annonce/find/{annonce}', name: 'app_annonce_id')]
    public function getId(Annonce $annonce, Commentary $commentary): Response
    {
        return $this->render('annonce/user/getId.html.twig', [
            'annonce' => $annonce,
            'commentary' => $commentary
        ]);
    }

    #[Route('/user/annonce/create', name: 'app_annonce_create')]
    public function create(Request $request): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($annonce);
            $this->em->flush();
            return $this->redirectToRoute('app_annonce');
        }

        return $this->render('annonce/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/user/annonce/update/{annonce}', name: 'app_annonce_update')]
    public function update(Annonce $annonce, Request $request): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($annonce);
            $this->em->flush();
            return $this->redirectToRoute('app_annonce');
        }
        return $this->render('annonce/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/annonce/delete/{annonce}', name: 'app_annonce_delete')]
    public function delete(Annonce $annonce, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->get('_token'))) {
            $this->em->remove($annonce);
            $this->em->flush();
        }

        return $this->redirectToRoute("app_annonce");
    }

  
}
