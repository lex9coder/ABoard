<?php

namespace App\Views\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Category\CategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;

class OfferController extends AbstractController
{

    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[Route('/api/offer/get_category', name:'api_offer_category', methods:['GET'])]
    public function get_category(): JsonResponse
    {
        $result['status'] = 'ok';
        $result['result'] = $this->categoryService->getList();
        return new JsonResponse($result);
    }

    #[Route('/api/offer/get_form', name:'api_offer_form', methods:['GET'])]
    public function get_form(): JsonResponse
    {
        $result['status'] = 'ok';
        $result['html'] = $this->renderView("offer/add/form.html.twig");
        return new JsonResponse($result);
    }
    

}
