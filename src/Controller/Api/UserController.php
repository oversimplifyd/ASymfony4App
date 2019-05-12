<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route(
     *     name="get_users",
     *     path="api/users",
     *     methods={"GET"},
     *   )
     */
    public function index(Request $request)
    {
        return $this->json($this->userRepository->findAll());
    }

    /**
     * @Route(
     *     name="add_user",
     *     path="api/users",
     *     methods={"POST"},
     *   )
     */
    public function addUser(Request $request)
    {
        //return $this->json($this->userRepository->findAll());
    }

    /**
     * @Route(
     *     name="delete_user",
     *     path="api/users/{id}",
     *     methods={"DELETE"},
     *     requirements={
     *         "id": "\d+"
     *     }
     *   )
     */
    public function deleteUser(Request $request, int $userId)
    {
        return $this->json($this->userRepository->findAll());
    }

    /**
     * @Route(
     *     name="assign_user",
     *     path=""/api/users/assign/{userId}/{groupId}",
     *     methods={"POST"},
     *     requirements={
     *         "userId": "\d+",
     *         "groupId": "\d+"
     *     }
     *   )
     */
    public function assignUserToGroup(Request $request, int $userId, int $groupId)
    {
        return $this->json($this->userRepository->findAll());
    }

    /**
     * @Route(
     *     name="remove_user",
     *     path=""/api/users/unassign/{userId}/{groupId}",
     *     methods={"POST"},
     *     requirements={
     *         "userId": "\d+",
     *         "groupId": "\d+"
     *     }
     *   )
     */
    public function removeUserFromGroup(Request $request, int $userId, int $groupId)
    {
        return $this->json($this->userRepository->findAll());
    }
}
