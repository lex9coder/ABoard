<?php

namespace App\Domain\User;

use App\Domain\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Service\Attribute\Required;

class UserService
{
    #[Required]
    public ManagerRegistry $doctrine;

    public function getUsers(string $term = '', int $page = 1, int $limit = 20): ?array
    {
        $users = $this->doctrine->getRepository(User::class)
                        ->findAllPagination($term, $page, $limit);
        
        $r = [];
        foreach ($users as $key => $user) {
            $r[] = $user->getJson();
        }
        return $r;
    }

}
