<?php

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Offer\OfferService;

class ProfileController extends AbstractController
{

    private OfferService $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }
    
    #[Route(path:'/profile', name:'app_profile')]
    public function index(): Response
    {

        $offers = $this->offerService->getByUser();

        return $this->render('profile/index.html.twig',[
            'user' => $this->getUser(),
            'offer_loop' => $offers
        ]);
    }

}
