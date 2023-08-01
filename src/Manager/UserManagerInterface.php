<?php

namespace App\Manager;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserManagerInterface
{
    public function create(User $user, UserPasswordHasherInterface $userPasswordHasher, string $formPassword, string $username): void;
    public function edit(User $user, UserPasswordHasherInterface $userPasswordHasher, string $formPassword, string $username): void;

}
