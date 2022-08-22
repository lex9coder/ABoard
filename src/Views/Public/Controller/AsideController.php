<?php

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Category\CategoryService;

class AsideController extends AbstractController
{

    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getLeftBlock(int $id)
    {
        $catalog_loop = $this->categoryService->getLeft($id);
        return $this->render("aside/left.html.twig", [
            'catalog_loop' => $catalog_loop
        ]);
    }

}
