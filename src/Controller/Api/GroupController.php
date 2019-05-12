<?php

namespace App\Controller\Api;

use App\Entity\Group;
use App\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GroupRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupController extends AbstractController
{

    const STATUS_SUCCESS = 'Success';
    const STATUS_FAILED = 'Failed';

    protected $groupRepository;
    protected $groupService;

    public function __construct(GroupRepository $groupRepository, GroupService $groupService)
    {
        $this->groupRepository = $groupRepository;
        $this->groupService = $groupService;
    }

    /**
     * @Route(
     *     name="get_groups",
     *     path="api/groups",
     *     methods={"GET"}
     *   )
     */
    public function index()
    {
        return $this->json($this->groupService->getGroups());
    }

    /**
     * @Route(
     *     name="add_group",
     *     path="api/groups",
     *     methods={"POST"}
     *   )
     */
    public function addGroup(Request $request, ValidatorInterface $validator)
    {
        $group = new Group();

        $group->setName($request->get('name'));
        $group->setCode($request->get('code'));
        $group->setCreatedAt(date_create('now'));
        $group->setUpdatedAt(date_create('now'));

        $errors = $validator->validate($group);

        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return $this->json($errorsString);
        }

        $this->groupRepository->create($group);

        return $this->json($group->getDetails());
    }

    /**
     * @Route(
     *     name="delete_group",
     *     path="api/groups/{id}",
     *     methods={"DELETE"},
     *     requirements={
     *         "id": "\d+"
     *     }
     *   )
     */
    public function deleteGroup(int $id)
    {
        if ($this->groupRepository->delete($id)) {
            return $this->json(self::STATUS_SUCCESS);
        }
        return $this->json(self::STATUS_FAILED, Response::HTTP_BAD_REQUEST);
    }
}
