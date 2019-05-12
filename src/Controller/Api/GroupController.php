<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GroupRepository;

class GroupController extends AbstractController
{

    protected $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * @Route(
     *     name="get_groups",
     *     path="api/groups",
     *     methods={"GET"},
     *   )
     */
    public function index(Request $request)
    {

    }

    /**
     * @Route(
     *     name="add_group",
     *     path="api/groups",
     *     methods={"POST"},
     *   )
     */
    public function addGroup(Request $request)
    {

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
    public function deleteGroup(Request $request, int $groupId)
    {

    }
}
