<?php


namespace App\Controller\Api;


use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    public function store(User $data, UserPasswordHasherInterface $passwordEncoder): User
    {
        $data->setUuid(Uuid::uuid4()->toString());
        $data->setPassword(
            $passwordEncoder->hashPassword(
                $data,
                $data->getPassword()
            )
        );
        return $data;
    }
}