<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
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

    #[Route('confirmation', name: 'confirmation')]
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', [

        ]);
    }
}