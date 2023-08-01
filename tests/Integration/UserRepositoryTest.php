<?php

namespace App\Tests\Integration;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);
    }

    public function testCreateUser()
    {
        $user = new User();
        $user->setEmail('user111@mail.fr');
        $username = 'testuser';
        $password = 'testpassword';

        $this->userRepository->create($user, $this->passwordHasher, $password, $username);

        // Retrieve the user from the repository to verify it was persisted
        $persistedUser = $this->userRepository->findOneBy(['username' => $username]);

        $this->assertInstanceOf(User::class, $persistedUser);
    }

    public function testEditUser()
    {
        $user = new User();
        $user->setEmail('user222@mail.fr');
        $username = 'testuser';
        $password = 'testpassword';
        $this->userRepository->create($user, $this->passwordHasher, $password, $username);

        $newUsername = 'newusername';
        $newPassword = 'newpassword';
        $this->userRepository->edit($user, $this->passwordHasher, $newPassword, $newUsername);

        // Retrieve the user from the repository to verify the update
        $updatedUser = $this->userRepository->findOneBy(['username' => $newUsername]);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertTrue($this->passwordHasher->isPasswordValid($updatedUser, $newPassword));
    }
}
