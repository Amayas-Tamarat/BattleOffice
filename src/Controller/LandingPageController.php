<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Payment;
use App\Form\CommandeType;
use App\Repository\ClientRepository;
use App\Repository\PaymentMethodRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7\Utils;

class LandingPageController extends AbstractController
{

    #[Route('/', name: 'landing_page')]
    public function index(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, PaymentMethodRepository $paymentMethodRepository): Response
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

            // dd($commande);
            $entityManager->persist($client);
            $entityManager->persist($adress);
            $entityManager->persist($payment);

            $entityManager->flush();
            // dd($client);

            $commandeData = [
                'id' => $commande->getId(),
                'product' => $commande->getProduit()->getName(),
                'payment_method' => $commande->getPayment()->getPaymentMethod()->getName(),
                'status' => $commande->getPayment()->getStatus(),
                'client' => [
                    'firstname' => $commande->getClient()->getName(),
                    'lastname' => $commande->getClient()->getLastName(),
                    'email' => $commande->getClient()->getMail(),
                ],
                'addresses' => [
                    'billing' => [
                        'address_line1' => $commande->getShippingAdress()->getAdress(),
                        'address_line2' => $commande->getShippingAdress()->getComplement(),
                        'city' => $commande->getShippingAdress()->getVille(),
                        'zipcode' => strval($commande->getShippingAdress()->getPostalCode()),
                        'country' => $commande->getShippingAdress()->getCountry()->getName(),
                        'phone' => $commande->getClient()->getPhone(),
                    ],
                    'shipping' => [
                        'address_line1' => $commande->getShippingAdress()->getAdress(),
                        'address_line2' => $commande->getShippingAdress()->getComplement(),
                        'city' => $commande->getShippingAdress()->getVille(),
                        'zipcode' => strval($commande->getShippingAdress()->getPostalCode()),
                        'country' => $commande->getShippingAdress()->getCountry()->getName(),
                        'phone' => $commande->getClient()->getPhone(),
                    ],
                ],
            ];

            $order = ['order'=>$commandeData];

            // dd($commandeData);

            $guzzleclient  =  new  GuzzleHttpClient([
                'base_uri'  =>  'https://api-commerce.simplon-roanne.com/',
                'verify' => false,
                'timeout'   =>  2.0,
            ]);
    
            $token = 'mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX';
    
            // Créez une requête avec l'en-tête d'autorisation
            try {
                // Préparez le contenu JSON sous forme de fichier
                $jsonData = $order;
            
                $jsonContent = json_encode($jsonData);
            
                $stream = Utils::streamFor($jsonContent);
            
                // $multipart = [
                //     [
                //         'name' => 'json_file',
                //         'contents' => $stream,
                //         'filename' => 'data.json',
                //     ],
                // ];
            
                $response = $guzzleclient->post('/order', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token, // Add the Bearer token to the request headers
                        'Content-Type' => 'application/json', // Set the Content-Type header
                    ],
                    'body' => $jsonContent, // Set the JSON data as the request body
                ]);
            
                // Récupérez la réponse de l'API
                $statusCode = $response->getStatusCode();
                $apiResponse = $response->getBody()->getContents();
                $apiResponse = json_decode($apiResponse, true);
                $apiCommandeId = $apiResponse['order_id'];
            
                // Traitez la réponse de l'API comme nécessaire
                // ...
            } catch (\Exception $e) {
                // Gérez les erreurs, par exemple, en affichant un message d'erreur
                echo 'Erreur lors de l\'appel à l\'API : ' . $e->getMessage();
            }

            // dd($apiCommandeId);

            $commande->setApiCommandeId($apiCommandeId);
            // dd($commande);



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
        return $this->render('landing_page/confirmation.html.twig', []);
    }

    #[Route('/emails/{id}', name: 'emails')]
    public function emails(ClientRepository $clientRepository, ProduitRepository $produitRepository)
    {


        // A CHANGER QUAND ON AURA FINI LE FORM
        $client = $clientRepository->findOneBy(array('id' => 1));
        $product = $produitRepository->findOneBy(array('id' => 1));
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
