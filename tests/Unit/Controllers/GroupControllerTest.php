<?php

class GroupControllerTest extends \Symfony\Bundle\FrameworkBundle\Tests\TestCase
{

    protected $controller;
    protected $groupService;
    protected $groupRepository;
    protected $request;

    protected $validator;

    public function setup()
    {
        $this->groupService = $this->createMock(\App\Service\GroupService::class);
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

        $this->controller = new \App\Controller\Api\GroupController(
            $this->groupRepository,
            $this->groupService
        );

        $this->controller->setContainer($container);
    }

    public function testIndexSuccess()
    {
        $this->groupService->expects($this->once())
            ->method('getGroups')
            ->will($this->returnValue($this->getTestGroup()));

        $this->assertEquals(
            json_encode($this->getTestGroup()),
            $this->controller->index()->getContent()
        );
    }

    public function testAddGroup()
    {
        $this->request->request->set('name', 'Test Group');
        $this->request->request->set('code', '123456');

        $this->groupRepository->expects($this->any())
            ->method('create')
            ->will($this->returnValue(true));

        $this->assertEquals(
            json_encode($this->getTestGroupDetails()),
            $this->controller->addGroup($this->request, $this->validator)->getContent()
        );
    }

    private function getTestGroup()
    {
        return [
            'Id' => null,
            'Name' => 'Test Group',
            'Code' => '123456'
        ];
    }

    private function getTestGroupDetails()
    {
        return [
            'id' => null,
            'name' => 'Test Group',
            'code' => '123456'
        ];
    }
}
