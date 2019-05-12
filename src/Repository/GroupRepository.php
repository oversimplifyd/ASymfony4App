<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{

    protected $entityManager;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Group::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function assignUser(int $groupId, User $user) :bool
    {
        try {

            $group = $this->findOneBy(array('id' => $groupId));
            if ($group) {
                $group->addUser($user);
            }
            $this->entityManager->persist($group);
            $this->entityManager->flush();

        } catch(ORMException $exception) {
            //Log here...
            return false;
        }

        return true;
    }

    public function unAssignUser(int $groupId, User $user) :bool
    {
        try {

            $group = $this->findOneBy(array('id' => $groupId));
            if ($group) {
                $group->removeUser($user);
            }
            $this->entityManager->persist($group);
            $this->entityManager->flush();

        } catch(ORMException $exception) {
            //Log here...
            return false;
        }

        return true;
    }

    public function create(Group $group) :bool
    {
        try {

            $this->entityManager->persist($group);
            $this->entityManager->flush();

        } catch(ORMException $exception) {
            return false;
        }

        return true;
    }

    public function delete(int $groupId) :bool
    {
        try {

            $group = $this->findOneBy(array('id' => $groupId));
            if ($group) {
                $this->entityManager->remove($group);
                $this->entityManager->flush();
            }

        } catch(ORMException $exception) {
            //Log here...
            return false;
        }

        return true;
    }
}
