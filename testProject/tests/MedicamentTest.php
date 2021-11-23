<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\MedicamentController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use App\Controller\ServiceController;
use App\Entity\Medicament;

class MedicamentTest extends TestCase
{
    public ServiceController $testServiceController;
    public Request $testRequest;
    public $expectedResult;
    public MedicamentController $testController;
    public Medicament $testMedicament;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->testEntityManager = $this->createMock(EntityManager::class);
        $this->testServiceController = $this->createMock(ServiceController::class);
        $this->testController = new MedicamentController($this->testServiceController);
        $this->testMedicament = new Medicament();
        $this->testMedicament->setMedicamentName(' ')
                             ->setSubstanceId(1)
                             ->setManufacturerId(1)
                             ->setPrice(123.56);
    }
    
    public function testShow():void
    {
        $result = $this->testController->show(0);
        $this->expectedResult = new Response('Not found',404);

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testCreate():void
    {
        $result = $this->testController->create($this->testMedicament);
        $this->expectedResult = 400;

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testEditForm():void
    {
        $request = Request::create('', 'POST', [$this->testMedicament]);
        $result = $this->testController->showEditForm($request, 0);
        $this->expectedResult = new Response('Not found', 404);

        $this->assertEquals($this->expectedResult, $result);
    }

    public function testEdit():void
    {
        $result = $this->testController->edit($this->testMedicament);
        $this->expectedResult = 400;

        $this->assertEquals($this->expectedResult, $result);
    }

    public function tearDown():void
    {
        parent::tearDown();
        \Mockery::close();
        gc_collect_cycles();
    }
}
