<?php

namespace App\Views\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\User\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/api/user/search', name:'api_user_search', methods:['GET'])]
    public function search(Request $request): JsonResponse
    {
        $term = $request->query->get('term', '');
        $result['result'] = $this->userService->getUsers($term);
        $result['status'] = 'ok';

        return new JsonResponse($result);
    } 

}
