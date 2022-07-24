<?php

declare(strict_types=1);

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route(path: '/', name: 'main', methods:['GET'])]
    public function main(): Response
    {
        throw $this->createNotFoundException('The product does not exist');
        return $this->render('main/index.html.twig');
    }

}