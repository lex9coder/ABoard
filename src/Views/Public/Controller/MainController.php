<?php

declare(strict_types=1);

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Category\CategoryService;
use App\Domain\Offer\OfferService;

class MainController extends AbstractController
{

    private CategoryService $categoryService;
    private OfferService $offerService;

    public function __construct(CategoryService $categoryService, OfferService $offerService)
    {
        $this->categoryService = $categoryService;
        $this->offerService = $offerService;
    }

    #[Route(path: '/', name: 'app_main', methods:['GET'])]
    public function main(): Response
    {
        $categories = $this->categoryService->getPageMain();
        $offers = $this->offerService->getPageMain();

        return $this->render('main/index.html.twig', [
            'catalogs' => $categories,
            'offers' => $offers
        ]);
    }

}