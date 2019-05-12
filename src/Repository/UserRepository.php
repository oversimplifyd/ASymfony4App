<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    protected $entityManager;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function create(User $user) :bool
    {
        try {

            $this->entityManager->persist($user);
            $this->entityManager->flush();

        } catch(ORMException $exception) {
            return false;
        }

        return true;
    }

    public function delete(int $userId) :bool
    {
        try {

            $user = $this->findOneBy(array('id' => $userId));
            if ($user) {
                $this->entityManager->remove($user);
                $this->entityManager->flush();
            }

        } catch(ORMException $exception) {
            //Log here...
            return false;
        }

        return true;
    }
}
