<?php

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Category\CategoryService;
use App\Domain\Offer\OfferService;

class CategoryController extends AbstractController
{

    private CategoryService $categoryService;
    private OfferService $offerService;
    public function __construct(CategoryService $categoryService, OfferService $offerService)
    {
        $this->categoryService = $categoryService;
        $this->offerService = $offerService;
    }

    #[Route('catalog/{path}', name:'app_catalog', requirements: ['path' => '.+'])]
    public function view(string $path): Response
    {

        $catalog = $this->categoryService->findByPath($path);
        if (!$catalog) {
            throw $this->createNotFoundException();
        }

        $offers = $this->offerService->findByCatalog($catalog->getId());

        return $this->render('catalog/view.html.twig', [
            'catalog' => $catalog,
            'offer_loop' => $offers
        ]);
    }

    public function getBreadcrumb(int $id)
    {
        $loop = $this->categoryService->getParents($id);
        return $this->render("include/_breadcrumb.html.twig", [
            'loop' => $loop
        ]);
    }

}
