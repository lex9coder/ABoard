<?php

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Category\CategoryService;

class ServiceController extends AbstractController
{

    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[Route('service/run', name:'app_service_run')]
    public function view()
    {
        $this->categoryService->serviceRunUpdatePath();
        echo "ok";
        exit;
    }

}
