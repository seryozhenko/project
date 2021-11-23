<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\ActiveSubstanceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use App\Controller\ServiceController;
use App\Entity\ActiveSubstance;

class ActiveSubstanceTest extends TestCase
{
    public ServiceController $testServiceController;
    public Request $testRequest;
    public $expectedResult;
    public ActiveSubstanceController $testController;

    public function setUp(): void
    {
        parent::setUp();
        $this->testEntityManager = $this->createMock(EntityManager::class);
        $this->testServiceController = $this->createMock(ServiceController::class);
        $this->testController = new ActiveSubstanceController($this->testServiceController);
        $this->testEntity = new ActiveSubstance();
        $this->testEntity->setSubstanceName(' ');
    }

    public function testShow():void
    {
        $result = $this->testController->show(0);
        $this->expectedResult = new Response('Not found',404);

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testCreate():void
    {
        $result = $this->testController->create($this->testEntity);
        $this->expectedResult = 400;

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testEdit():void
    {
        $result = $this->testController->edit($this->testEntity);
        $this->expectedResult = 400;

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testEditForm():void
    {
        $request = Request::create('/0/edit','POST', []);
        $result = $this->testController->showEditForm($request, 0);
        $this->expectedResult = new Response('Not Found', 404);

        $this->assertEquals($this->expectedResult, $result);
    }
    
    public function tearDown():void
    {
        parent::tearDown();
        \Mockery::close();
        gc_collect_cycles();
    }
}
