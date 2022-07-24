<?php

namespace App\Tests\Resourse\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $new = (new UserFactory())->create();
        // $manager->flush();
    }
}