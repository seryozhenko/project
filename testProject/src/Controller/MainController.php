<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Server(url="localhost:85")
 */
/**
 * @OA\Info(title="My First API", version="0.1")
 */
/**
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"main"},
     *     operationId="index",
     *     description="Show main menu",
     *     @OA\Response(
     *         response=200,
     *         description="Rendered page"
     *     )
     * )
     * @Route("/", name="main_manu", methods={"GET"})
     */
    public function index():Response
    {
        return $this->render("base.html.twig");
    }
}