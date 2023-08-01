<?php

namespace App\Repository;

use App\Entity\User;
use App\Manager\UserManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserManagerInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function create(User $user, UserPasswordHasherInterface $userPasswordHasher, string $formPassword, string $username): void
    {
        $user->setPassword($userPasswordHasher->hashPassword($user, $formPassword))
            ->setUsername($username);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function edit(User $user, UserPasswordHasherInterface $userPasswordHasher, string $formPassword, string $username): void
    {

        $user->setPassword($userPasswordHasher->hashPassword($user, $formPassword))
            ->setUsername($username);

        $this->_em->flush();
    }

}
