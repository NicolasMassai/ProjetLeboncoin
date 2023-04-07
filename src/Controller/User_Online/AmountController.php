<?php

namespace App\Controller\User_Online;

use App\Entity\Bank;
use App\Form\BankType;
use App\Repository\BankRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AmountController extends AbstractController
{

    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/user/amount', name: 'app_amount')]
    public function index(BankRepository $bankRepository): Response
    {
        $bankRepository = $bankRepository->findAll();

        return $this->render('amount/user/index.html.twig', [
            'amounts' => $bankRepository,
        ]);
    }

    #[Route('/user/amount/find/{amount}', name: 'app_amount_id')]
    public function getId(Bank $amount): Response
    {
        return $this->render('amount/user/getId.html.twig', [
            'amount' => $amount
        ]);
    }


    #[Route('/user/amount/create', name: 'app_amount_create')]
    public function create(Request $request): Response
    {
        $amount = new Bank();
        $form = $this->createForm(BankType::class, $amount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($amount);
            $this->em->flush();
            return $this->redirectToRoute('app_amount');
        }

        return $this->render('amount/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
