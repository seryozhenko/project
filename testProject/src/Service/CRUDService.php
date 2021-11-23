<?php
namespace App\Service;

use App\Entity\EntityInterface;
use App\Controller\ServiceController;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class CRUDService
{
    private ObjectManager $entityManager;
    private $repository;

    public function __construct(ServiceController $controller, string $class)
    {
        $this->setEntityManager($controller->getManager());
        $this->setRepository($class);
    }

    public function setEntityManager(ObjectManager $manager):void
    {
        $this->entityManager = $manager;
    }

    public function setRepository($class):void
    {
        $this->repository = $this->entityManager->getRepository($class);
    }

    public function add(EntityInterface $entity):void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove($entity):void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function update():void
    {
        $this->entityManager->flush();
    }

    public function readAll():array
    {
        return $this->repository->findAll();
    }

    public function readOne(int $id, $class = '') : ?EntityInterface
    {
        if($class != ''){
            $repository = $this->entityManager->getRepository($class);
            return $repository->find($id);
        }
        
        return $this->repository->find($id);
    }
}