<?php

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Category\CategoryService;
use App\Domain\Offer\OfferService;

class PanelController extends AbstractController
{

    private CategoryService $categoryService;
    private OfferService $offerService;

    public function __construct(CategoryService $categoryService, OfferService $offerService)
    {
        $this->categoryService = $categoryService;
        $this->offerService = $offerService;
    }

    #[Route('panel', name:'app_panel_main')]
    public function main(): Response
    {

        $offers = $this->offerService->getAll();

        return $this->render('panel/main.html.twig', [
            'offer_loop' => $offers
        ]);
    }

}
