<?php

namespace App\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var Task $task */
        $task = $subject;
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $task->getUserUuid() === $user->getUuid();
    }
}
