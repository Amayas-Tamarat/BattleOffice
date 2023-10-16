<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Payment;
use App\Entity\Produit;
use App\Form\AdressType;
use App\Form\ClientType;
use App\Form\CommandeType;
use App\Form\ProduitType;
use App\Repository\ClientRepository;
use App\Repository\PaymentMethodRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{

    #[Route('/', name: 'landing_page')]
    public function index(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, PaymentMethodRepository $paymentMethodRepository) :Response
    {
        //Your code here
       
        $produits = $produitRepository->findAll();
        $paymentMethods = $paymentMethodRepository->findAll();

        $commande = new Commande();
        $formCommande = $this->createForm(CommandeType::class, $commande);
        $formCommande->handleRequest($request);

        $payment = new Payment();
        

        $client = $commande->getClient();
        $adress = $commande->getShippingAdress();
        // dd($client);

        if ($formCommande->isSubmitted() && $formCommande->isValid()) {

        $selectedProductID = $request->request->get('selected_product_id');
        $produit = $produitRepository->find($selectedProductID);

        $selectedPaymentMethodName = $request->request->get('selected_payment_method_name');
        $paymentMethod = $paymentMethodRepository->findOneBy(['name' => $selectedPaymentMethodName]);

        $commande->setProduit($produit);
        $payment->setPaymentMethod($paymentMethod);
        $payment->setBillingAdress($adress);
        $commande->setPayment($payment);

        // $commande->setPayment()

        dd($commande);
        // $entityManager->persist($client);
        // $entityManager->persist($adress);
        $entityManager->persist($payment);
        
        $entityManager->flush();
        // dd($client);
        

        $entityManager->persist($commande);
        $entityManager->flush();
        }

        
        return $this->render('landing_page/index_new.html.twig', [
            'produits' => $produits,
            'formCommande' => $formCommande,
            'paymentMethods' => $paymentMethods,
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


    // #[Route('/test', name: 'test')]
    // public function test(Request $request, EntityManagerInterface $entityManager) : Response
    // {
    //     $commande = new Commande();
    //     $formCommande = $this->createForm(CommandeType::class, $commande);
    //     $formCommande->handleRequest($request);
        

    //     $client = $commande->getClient();
    //     $adress = $commande->getShippingAdress();
    //     $commande->setProduit($produit);
    //     // dd($client);

    //     if ($formCommande->isSubmitted() && $formCommande->isValid()) {

    //     $entityManager->persist($client);
    //     $entityManager->persist($adress);
        
    //     $entityManager->flush();
    //     // dd($client);
        

    //     $entityManager->persist($commande);
    //     $entityManager->flush();
    //     }

    //     return $this->render('landing_page/test.html.twig', [
    //         'formCommande' => $formCommande
    //     ]);
    // }
}