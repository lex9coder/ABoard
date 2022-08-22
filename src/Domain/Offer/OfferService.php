<?php

namespace App\Domain\Offer;

use App\Domain\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Domain\Entity\OfferImage;

class OfferService
{

    #[Required]
    public ManagerRegistry $doctrine;

    private $security;
    private $uploadDir;
    public function __construct(string $uploadDir, Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
        $this->uploadDir = $uploadDir;
    }

    public function getAll(): array
    {
        return $this->doctrine->getRepository(Offer::class)->findAll();
    }

    public function getPageMain(): array
    {
        return $this->doctrine->getRepository(Offer::class)->findBy([
            'active' => true
        ]);
    }

    public function getByUser(int $id = 0): ?array
    {

        $user_id = $id;
        if (!$user_id) {
            $user = $this->security->getUser();
            $user_id = $user?->getId();
        }

        if ($user_id) {
            return $this->doctrine->getRepository(Offer::class)->findBy([
                'user' => $user_id
            ]);
        }
    }

    public function findOneById(int $id)
    {
        $offer = $this->doctrine->getRepository(Offer::class)->findOneBy([
            'id' => $id
        ]);
        if (!$offer) {
            return false;
        }
        if ($offer->isActive()) {
            return $offer;
        }
        $user = $this->security->getUser();
        if ($user
            && $offer->getUser()
            && $offer->getUser()->getId() == $user->getId()
        ) {
            return $offer;
        }
        return false;
    }

    public function findByCatalog(int $id)
    {
        return $this->doctrine->getRepository(Offer::class)->findBy([
            'active' => true
        ]);
    }


    public function create( array $params ): ?Offer
    {
        $offer = new Offer();
        $offer->setCategory($params['category']);

        $user = $this->security->getUser();
        $offer->setUser($user);

        $offer->setTitle($params['title']);

        $slugger = new AsciiSlugger();
        $slug = strtolower( $slugger->slug($offer->getTitle()) );
        $offer->setSlug($slug);

        $offer->setSlug('offer');

        // $offer->setAddress($params['address']);
        $offer->setPrice($params['price']);
        $offer->setPhone($params['phone']);
        $offer->setDescription($params['description']);

        $this->save($offer);
        return $offer;
    }

    public function addImage( Offer $offer, UploadedFile $file ) {
        $newFilename = $offer->getId().'-'.uniqid().'.'.$file->guessExtension();
        $file->move($this->uploadDir.'/offers', $newFilename);

        $image = new OfferImage();
        $image->setFilename($newFilename);
        $offer->addImage($image);

        $this->save($image);
    }

    public function save( $obj ) {
        $this->doctrine->getManager()->persist($obj);
        $this->doctrine->getManager()->flush();
    }

}
