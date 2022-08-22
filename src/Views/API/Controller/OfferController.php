<?php

namespace App\Views\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Domain\Category\CategoryService;
use App\Domain\Offer\OfferService;
use Symfony\Component\HttpFoundation\Request;

class OfferController extends AbstractController
{

    private CategoryService $categoryService;
    private OfferService $offerService;
    public function __construct(CategoryService $categoryService, OfferService $offerService)
    {
        $this->categoryService = $categoryService;
        $this->offerService = $offerService;
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
    
    #[Route('/api/offer/get_phone', name:'api_offer_phone', methods:['GET'])]
    public function get_phone(Request $request): JsonResponse
    {
        $result = [];
        $id = $request->query->get('id');
        $offer = $this->offerService->findOneById($id);
        if ($offer) {
            $result['status'] = 'ok';
            $result['phone'] = $offer->getPhone();
        } else {
            $result['status'] = 'error';
        }

        return new JsonResponse($result);
    }

}
