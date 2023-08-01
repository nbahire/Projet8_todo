<?php

namespace App\Tests\Functional\Traits;

use App\Repository\UserRepository;

trait AuthentificationTestTrait
{
    /**
     * @throws \Exception
     */
    public function loginUser(): void
    {

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.fr');

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
