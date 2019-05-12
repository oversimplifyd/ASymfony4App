<?php

class UserControllerTest extends \Symfony\Bundle\FrameworkBundle\Tests\TestCase
{

    protected $controller;
    protected $userRepository;
    protected $userService;
    protected $groupRepository;
    protected $request;

    public function setup()
    {
        $this->userRepository = $this->createMock(\App\Repository\UserRepository::class);
        $this->userService = $this->createMock(\App\Service\UserService::class);
        $this->groupRepository =  $this->createMock(\App\Repository\GroupRepository::class);
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);

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

    }

    public function testAddUserFailure()
    {

    }

    public function testAssignUserToGroup()
    {

    }

    public function testUnassignUserToGroup()
    {

    }

    private function getTestUsers()
    {
        return [
            'Id' => 1,
            'Full Name' => "James",
            'Email' => "james@email.com"
        ];
    }
}
