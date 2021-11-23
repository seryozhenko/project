<?php
namespace App\Controller;
                           
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManager;

class ServiceController extends AbstractController
{
    private $em;
    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
    }
    public function getManager():EntityManager
    {
        return $this->em;
    }
    public function getRepository(string $class = '') : ObjectRepository
    {
        return $this->em->getRepository($class);
    }

}