<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ['TASK_DELETE','TASK_EDIT']) && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $loggedUser = $token->getUser();

        if (!$loggedUser instanceof UserInterface) {
            return false;
        }

        $taskOwner = $subject->getUser();

        if (null === $taskOwner) {
            return $this->security->isGranted('ROLE_ADMIN');
        }

        return $taskOwner === $loggedUser;
    }
}
