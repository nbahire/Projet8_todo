<?php

namespace App\Tests\Controller\Traits;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;


trait AuthentificationTrait
{

    /**
     * @throws \Exception
     */
    public function loginUser(): void
    {

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@mail.fr');

        $this->client->loginUser($testUser);
    }

    /**
     * @throws \Exception
     */
    public function loginAdmin(): void
    {

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@mail.fr');

        $this->client->loginUser($testUser);
    }

}
