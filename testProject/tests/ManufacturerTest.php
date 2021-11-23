<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\ManufacturerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use App\Controller\ServiceController;
use App\Entity\Manufacturer;

class ManufacturerTest extends TestCase
{
    public ServiceController $testServiceController;
    public ManufacturerController $testController;
    public Request $testRequest;
    public $expectedResult;
    public Manufacturer $manufacturer;

    public function setUp(): void
    {
        parent::setUp();
        $this->testEntityManager = $this->createMock(EntityManager::class);
        $this->testServiceController = $this->createMock(ServiceController::class);
        $this->testController = new ManufacturerController($this->testServiceController);
        $this->manufacturer = new Manufacturer();
        $this->manufacturer->setManufacturerName(' ')
                           ->setLink('https://google.com');
    }

    public function testShow():void
    {
        $result = $this->testController->show(0);
        $this->expectedResult = new Response('Not found',404);

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testCreate():void
    {
        $result = $this->testController->create($this->manufacturer);
        $this->expectedResult = 400;

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testEdit():void
    {
        $result = $this->testController->edit($this->manufacturer);
        $this->expectedResult = 400;

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testEditForm():void
    {
        $request = Request::create('edit/0', 'POST', [$this->manufacturer]);
        $result = $this->testController->showEditForm($request, 0);
        $this->expectedResult = new Response('Not Found', 404);

        $this->assertEquals($this->expectedResult, $result);
    }

    public function tearDown():void
    {
        parent::tearDown();
        \Mockery::close();
        $this->dataService = null;
        gc_collect_cycles();
    }
}
