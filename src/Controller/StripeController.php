<?php
 
namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
 
class StripeController extends AbstractController
{
    // #[Route('/stripe/{price}', name: 'app_stripe')]
    // public function index(ProduitRepository $produitRepository, Request $request, Produit $produit): Response
    // {
        
    //     $productPrice = $produit->getPrice();
    //     return $this->render('stripe/index.html.twig', [
    //         'api_key' => $_ENV["API_KEY"],
    //         'price' => $productPrice,
    //     ]);

    //     dd($productPrice);
    // }

    #[Route('/stripe/{apiCommandeId}/{productPrice}', name: 'app_stripe')]
    public function index(int $apiCommandeId, int $productPrice): Response
    {
        return $this->render('stripe/index.html.twig', [
            'api_key' => $_ENV["API_KEY"],
            'apiCommandeId' => $apiCommandeId,
            'productPrice' => $productPrice,
        ]);
    }
 
 
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('app_stripe', [], Response::HTTP_SEE_OTHER);
    }
}