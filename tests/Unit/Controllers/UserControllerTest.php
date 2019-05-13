<?php

class UserControllerTest extends \Symfony\Bundle\FrameworkBundle\Tests\TestCase
{

    protected $controller;
    protected $userRepository;
    protected $userService;
    protected $groupRepository;
    protected $request;

    protected $validator;

    public function setup()
    {
        $this->userRepository = $this->createMock(\App\Repository\UserRepository::class);
        $this->userService = $this->createMock(\App\Service\UserService::class);
        $this->groupRepository =  $this->createMock(\App\Repository\GroupRepository::class);
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);

        $this->validator = \Symfony\Component\Validator\Validation::createValidator();

        $container->expects($this->any())
            ->method("getParameter")
            ->will($this->returnValue(true));

        $container->expects($this->any())
            ->method("get")
            ->will($this->returnValue(true));

        $this->request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        $this->controller = new \App\Controller\Api\UserController(
            $this->userRepository,
            $this->groupRepository,
            $this->userService
        );

        $this->controller->setContainer($container);
    }

    public function testIndexSuccess()
    {
        $this->userService->expects($this->any())
            ->method('getAllUsers')
            ->will($this->returnValue($this->getTestUsers()));

        $this->assertEquals(
            json_encode($this->getTestUsers()),
            $this->controller->index()->getContent()
        );
    }

    public function testAddUserSuccess()
    {
        $this->request->request->set('name', 'Test');
        $this->request->request->set('email', 'james@email.com');

        $this->userRepository->expects($this->any())
            ->method('create')
            ->will($this->returnValue(true));

        $this->assertEquals(
            json_encode($this->getTestUerDetails()),
            $this->controller->addUser($this->request, $this->validator)->getContent()
        );
    }

    public function testAssignUserToGroupSuccess()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new \App\Entity\User()));

        $this->groupRepository->expects($this->any())
            ->method('assignUser')
            ->will($this->returnValue(true));

        $this->userService->expects($this->any())
            ->method('getUserWithGroups')
            ->will($this->returnValue($this->getUserWithGroups()));

        $this->assertEquals(
            json_encode($this->getUserWithGroups()),
            $this->controller->assignUserToGroup($this->request, 1, 2)->getContent()
        );
    }

    public function testAssignUserToGroupFailure()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new \App\Entity\User()));

        $this->groupRepository->expects($this->once())
            ->method('assignUser')
            ->will($this->returnValue(false));

        $this->userService->expects($this->any())
            ->method('getUserWithGroups')
            ->will($this->returnValue($this->getUserWithGroups()));

        $this->assertEquals(
            json_encode('Failed'),
            $this->controller->assignUserToGroup($this->request, 1, 2)->getContent()
        );
    }

    public function testUnassignUserToGroupSuccess()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new \App\Entity\User()));

        $this->groupRepository->expects($this->once())
            ->method('unAssignUser')
            ->will($this->returnValue(true));

        $this->userService->expects($this->any())
            ->method('getUserWithGroups')
            ->will($this->returnValue($this->getUserWithGroups()));

        $this->assertEquals(
            json_encode($this->getUserWithGroups()),
            $this->controller->removeUserFromGroup($this->request, 1, 2)->getContent()
        );
    }

    public function testUnassignUserToGroupFailure()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new \App\Entity\User()));

        $this->groupRepository->expects($this->any())
            ->method('unAssignUser')
            ->will($this->returnValue(false));

        $this->userService->expects($this->any())
            ->method('getUserWithGroups')
            ->will($this->returnValue($this->getUserWithGroups()));

        $this->assertEquals(
            json_encode('Failed'),
            $this->controller->removeUserFromGroup($this->request, 1, 2)->getContent()
        );
    }

    private function getTestUsers()
    {
        return [
            'Id' => 1,
            'Full Name' => "James",
            'Email' => "james@email.com"
        ];
    }


    private function getTestUerDetails()
    {
        return [
            'id' => null,
            'name' => 'Test',
            'email' => 'james@email.com',
        ];
    }

    private function getUserWithGroups()
    {
        return [
            'id' => null,
            'name' => 'Test',
            'email' => 'james@email.com',
            'groups' => [
                'id' => null,
                'name' => 'Test Group',
                'code' => '923923'
            ]
        ];
    }
}
