<?php

namespace App\Tests\Fixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $encoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = $this->createAdminUser();
        $manager->persist($adminUser);

        $basicUser = $this->createBasicUser();
        $manager->persist($basicUser);

        $manager->flush();

        $manager->persist($this->createAdminTask($adminUser));
        $manager->persist($this->createBasicTask($basicUser));
        $manager->persist($this->createDoneTask($basicUser));

        $manager->flush();
    }

    private function createAdminUser(): User
    {
        $adminUser = new User;
        return $adminUser
            ->setEmail('admin@email.fr')
            ->setPassword($this->encoder->hashPassword($adminUser, 'admin'))
            ->setRole('ROLE_ADMIN')
            ->setUsername('admin');
    }

    private function createBasicUser(): User
    {
        $basicUser = new User;
        return $basicUser
            ->setEmail('user@email.fr')
            ->setPassword($this->encoder->hashPassword($basicUser, 'user'))
            ->setRole('ROLE_USER')
            ->setUsername('utilisateur basique');
    }

    private function createUnlinkedTask(User $anonymous): Task
    {
        $anonymousTask = new Task;
        return $anonymousTask->setTitle('Tache anonyme')
            ->setContent('Cette tache n\'est attachée à aucun utilisateur particulier')
        ;
    }

    private function createAdminTask(User $admin): Task
    {
        $adminTask = new Task;
        return $adminTask->setTitle('Tache administrateur')
            ->setContent('Cette tache appartient à l\'administrateur.')
            ->setOwner($admin)
        ;
    }

    private function createBasicTask(User $user): Task
    {
        $basicTask = new Task;
        return $basicTask->setTitle('Tache classique')
            ->setContent('Cette tache appartient à un utilisateur normal.')
            ->setOwner($user)
        ;
    }

    private function createDoneTask(User $user): Task
    {
        $doneTask = new Task;
        $doneTask->toggle(true);
        return $doneTask->setTitle('Tache complète')
            ->setContent('Cette tache est finie.')
            ->setOwner($user)
        ;
    }
}
