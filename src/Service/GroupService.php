<?php

namespace App\Service;

use App\Repository\GroupRepository;

class GroupService
{
    protected $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getGroups()
    {
        $results = [];
        $groups = $this->groupRepository->findAll();

        foreach ($groups as $group) {
            $results[] = [
                'Id' => $group->getId(),
                'Name' => $group->getName(),
                'Code' => $group->getCode()
            ];
        }

        return $results;
    }
}
