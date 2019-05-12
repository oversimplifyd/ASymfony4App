<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        $results = [];

        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            if ($user->getRoles()[0] !== 'ROLE_ADMIN') {
                $results[] = [
                    'Full Name' => $user->getName(),
                    'Email' => $user->getEmail()
                ];
            }
        }

        return $results;
    }

    public function getUserWithGroups(User $user)
    {
        $groups = [];
        foreach ($user->getGroups() as $group) {
            $groups[] = [
                'name' => $group->getName(),
                'code' => $group->getCode()
            ];
        }

        return [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'date_added' => $user->getDateAdded(),
            'group' => $groups
        ];
    }
}