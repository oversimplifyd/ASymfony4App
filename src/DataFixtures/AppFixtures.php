<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

class AppFixtures extends Fixture
{
    protected $faker;
    protected $manager;

    public function __construct(ObjectManager $manager)
    {
        $this-> faker = $faker = Faker::create();
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $this->createAdmin();
        $this->createGroups();
    }

    private function createAdmin()
    {
        return $this->createUsers(true);
    }

    private function createUsers(bool  $isAdmin = false) :array
    {
        $users = [];

        if ($isAdmin) {
            $user = new User();
            $user->setEmail('admin@internations.com');
            $user->setName('Admin');
            $user->setIsAdmin(true);
            $user->setDateAdded($this->faker->dateTime);

            $this->manager->persist($user);

            $this->createToken($user);

        } else {
            for ($i = 0; $i < 3; $i++) {
                $user = new User();
                $user->setEmail($this->faker->email);
                $user->setName($this->faker->name);
                $user->setDateAdded($this->faker->dateTime);
                $users[] = $user;
                $this->manager->persist($user);
            }
        }
        $this->manager->flush();

        return $users;
    }

    private function createGroups(int $count = 3)
    {
        for ($i = 0; $i < $count; $i++) {
            $group = new Group();
            $group->setName($this->faker->safeColorName);
            $group->setCode($this->faker->randomNumber(6));
            $group->setCreatedAt($this->faker->dateTime);
            $group->setUpdatedAt($this->faker->dateTime);
            $users = $this->createUsers();
            foreach ($users as $user) {
                $group->addUser($user);
            }
            $this->manager->persist($group);
        }
        $this->manager->flush();
    }

    public function createToken(User $user)
    {
        $token = new Token();
        $token->setUserToken(bin2hex(random_bytes(64)));
        $token->setUser($user);

        $this->manager->persist($token);

        $this->manager->flush();
    }
}
