<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    protected $userRepository;
    protected $groupRepository;
    protected $userService;

    const STATUS_SUCCESS = 'Success';
    const STATUS_FAILED = 'Failed';

    public function __construct(
        UserRepository $userRepository,
        GroupRepository $groupRepository,
        UserService $userService
    ) {

        $this->userRepository = $userRepository;
        $this->userService = $userService;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @Route(
     *     name="get_users",
     *     path="api/users",
     *     methods={"GET"}
     *   )
     */
    public function index()
    {
        return $this->json($this->userService->getAllUsers());
    }

    /**
     * @Route(
     *     name="add_user",
     *     path="api/users",
     *     methods={"POST"}
     *   )
     */
    public function addUser(Request $request, ValidatorInterface $validator)
    {
        $user = new User();

        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setIsAdmin(false);
        $user->setDateAdded(date_create('now'));

        $errors = $validator->validate($user);

        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return $this->json($errorsString);
        }

        $this->userRepository->create($user);

        return $this->json($user->getDetails());
    }

    /**
     * @Route(
     *     name="assign_user",
     *     path="/api/users/assign/{userId}/{groupId}",
     *     methods={"POST"},
     *     requirements={
     *         "userId": "\d+",
     *         "groupId": "\d+"
     *     }
     *   )
     */
    public function assignUserToGroup(Request $request, int $userId, int $groupId)
    {
        $user = $this->userRepository->findOneBy(array('id' => $userId));
        if ($this->groupRepository->assignUser($groupId, $user)) {
            return $this->json($this->userService->getUserWithGroups($user));
        }

        return $this->json(self::STATUS_FAILED, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route(
     *     name="remove_user",
     *     path="/api/users/unassign/{userId}/{groupId}",
     *     methods={"POST"},
     *     requirements={
     *         "userId": "\d+",
     *         "groupId": "\d+"
     *     }
     *   )
     */
    public function removeUserFromGroup(Request $request, int $userId, int $groupId)
    {
        $user = $this->userRepository->findOneBy(array('id' => $userId));
        if ($this->groupRepository->unAssignUser($groupId, $user)) {
            return $this->json($this->userService->getUserWithGroups($user));
        }

        return $this->json(self::STATUS_FAILED, Response::HTTP_BAD_REQUEST);
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
    public function deleteUser(Request $request, int $id)
    {
        if ($this->userRepository->delete($id)) {
            return $this->json(self::STATUS_SUCCESS);
        }
        return $this->json(self::STATUS_FAILED, Response::HTTP_BAD_REQUEST);
    }
}
