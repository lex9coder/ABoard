<?php

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Category\CategoryService;

class PageHeaderController extends AbstractController
{
    public function header(CategoryService $categoryService): Response
    {
        return $this->render('include/page/_header.html.twig', [
            'menu' => $categoryService->getMenuTop()
        ]);
    }
}
