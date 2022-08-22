<?php

namespace App\Views\Public\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Category\CategoryService;
use App\Domain\Offer\OfferService;

class OfferController extends AbstractController
{

    private CategoryService $categoryService;
    private OfferService $offerService;
    public function __construct(CategoryService $categoryService, OfferService $offerService)
    {
        $this->categoryService = $categoryService;
        $this->offerService = $offerService;
    }

    #[Route('/offer/{slug}-{id}.html', name:'offer_view')]
    public function view(string $slug, int $id): Response
    {
        $offer = $this->offerService->findOneById($id);
        if (!$offer) {
            throw $this->createNotFoundException();
        }
        if ($offer->getSlug() != $slug) {
            throw $this->createNotFoundException();
        }

        $offer->setViewCount( $offer->getViewCount() + 1 );
        $this->offerService->save($offer);
        
        return $this->render('offer/view.html.twig', [
            'offer' => $offer
        ]);
    }

    #[Route('/addoffer', name:'offer_add')]
    public function add(Request $request): Response
    {

        $errors = [];
        if ($request->isMethod('POST')) {

            $category_id = $request->request->getInt("category_id", 0);
            $category = $this->categoryService->findOneById($category_id);

            if (!$category) {
                $errors[] = 'Не выбрана категория';
            }

            $params['title'] = $request->request->get("title");
            $params['phone'] = $request->request->get("phone");
            $params['price'] = $request->request->getInt("price", 0);
            $params['address'] = $request->request->get("address");
            $params['description'] = $request->request->get("description");

            if (!$params['title'] || !$params['phone'] ) {
                $errors[] = 'Заполните поля';
            }

            if (!$errors) {
                $params['category'] = $category;
                $offer = $this->offerService->create($params);

                $images = $request->files->get('dropped_images');
                foreach ($images as $key => $file) {
                    $this->offerService->addImage($offer, $file);
                }

                return $this->redirectToRoute('offer_view', [
                    'id' => $offer->getId(),
                    'slug' => $offer->getSlug()
                ]);
            }
        }

        return $this->render('offer/add.html.twig', [
            'errors' => $errors
        ]);
    }

    public function getBreadcrumb(int $id)
    {
        $offer = $this->offerService->findOneById($id);
        $loop = $this->categoryService->getParents($offer->getCategory()->getId());

        array_push($loop, [
            'title' => $offer->getTitle()
        ]);

        return $this->render("include/_breadcrumb.html.twig", [
            'loop' => $loop
        ]);
    }

}
