<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{

    #[Route('/', name: 'landing_page')]
    public function index(Request $request, ProduitRepository $produitRepository) :Response
    {
        //Your code here
        
        $produits = $produitRepository->findAll();
        return $this->render('landing_page/index_new.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', [
        ]);
    }

    #[Route('/emails/{id}', name: 'emails')]
    public function emails(ClientRepository $clientRepository, ProduitRepository $produitRepository)
    {


        // A CHANGER QUAND ON AURA FINI LE FORM
        $client = $clientRepository->findOneBy(array ('id'=>1));
        $product = $produitRepository->findOneBy(array ('id'=>1));
        return $this->render('emails/confirmation.html.twig', [
            'client' => $client,
            'product' => $product,
        ]);
    }


    #[Route('/test', name: 'test')]
    public function test(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $adress = new Adress();
        $formAdress = $this->createForm(AdressType::class, $adress);
        $formAdress->handleRequest($request);
        // dd($adress);

        $entityManager->persist($adress);
        $entityManager->flush();

        return $this->render('landing_page/test.html.twig', [
            'formAdress' => $formAdress
        ]);
    }
}