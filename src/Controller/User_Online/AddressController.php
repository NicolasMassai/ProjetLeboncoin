<?php

namespace App\Controller\User_Online;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    


class AddressController extends AbstractController
{

    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user/address', name: 'app_address')]
    public function index(AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findAll();

        return $this->render('address/user/index.html.twig', [
            'address_s' => $address,
        ]);
    }

    #[Route('/user/address/find/{address}', name: 'app_address_id')]
    public function getId(Address $address): Response
    {
        return $this->render('address/user/getId.html.twig', [
            'address' => $address
        ]);
    }
    
    #[Route('/user/address/create', name: 'app_address_create')]
    public function create(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($address);
            $this->em->flush();
            return $this->redirectToRoute('app_address');
        }

        return $this->render('address/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

  
    #[Route('/user/address/update/{address}', name: 'app_address_update')]
    public function update(Address $address, Request $request): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($address);
            $this->em->flush();
            return $this->redirectToRoute('app_address');
        }
        return $this->render('address/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/address/delete/{address}', name: 'app_address_delete')]
    public function delete(Address $address, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->get('_token'))) {
            $this->em->remove($address);
            $this->em->flush();
        }

        return $this->redirectToRoute("app_address");
    }
/*
    public function delete(Address $address): RedirectResponse
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        return $this->redirectToRoute("app_address");


    }*/

}
