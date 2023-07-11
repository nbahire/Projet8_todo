<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        $faker = new Factory();
        $faker = $faker::create('fr_FR');

        for ($i = 0; $i <= 10; $i++) {
            $user = new User();

            $user->setUsername($i < 10 ? $faker->userName() : 'admin')
                ->setEmail($i < 10 ? $faker->email : 'admin@mail.fr')
                ->setPassword($i < 10 ? $this->userPasswordHasher->hashPassword($user, 'user') : $this->userPasswordHasher->hashPassword($user, 'admin'))
                ->setRoles($i < 10 ? ['ROLE_USER'] : ['ROLE_ADMIN'])
            ;
            $manager->persist($user);
            for ($j = 0; $j <= 5; $j++) {
                $task = new Task();
                $task->setUser($user);
                $task->setContent($faker->paragraphs(4, true));
                $task->setTitle($faker->words(3, true));
                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}
