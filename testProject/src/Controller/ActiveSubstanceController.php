<?php

namespace App\Controller;

use App\Entity\ActiveSubstance;
use App\Form\ActiveSubstanceType;
use App\Service\CRUDService;
use App\Controller\ServiceController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
 * @OA\Get(
 *     path="/active_substance"
 * )
 */
/**
 * @Route("/active_substance")
 */
class ActiveSubstanceController extends AbstractController
{
    protected CRUDService $serviceCRUD;

    public function __construct(ServiceController $serviceController)
    {
        $this->serviceCRUD = new CRUDService($serviceController, ActiveSubstance::class);
    }
    /**
     * @OA\GET(
     *     path="/active_substance/",
     *     tags={"active_substance"},
     *     operationId="list",
     *     description="Show all active substances in array",
     *     @OA\Response(
     *         response=200,
     *         description="Rendered page"
     *     )
     * )
     * @Route("/", name="active_substance_list", methods={"GET"})
     */
    public function list(): Response
    {
        $activeSubstances = $this->serviceCRUD->readAll();
        
        return $this->render('active_substance/list.html.twig', [
            'active_substances' => $activeSubstances
        ]);
    }

    /**
     * @OA\Post(
     *     path="/active_substance/new",
     *     tags={"active_substance"},
     *     operationId="create",
     *     description="Save new object",
     *     @OA\Response(
     *         response=202,
     *         description="Created"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid name"
     *     ),
     *     @OA\Parameter(
     *         name="body",
     *         in="path",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="substanceName", type="string")
     *         )
     *     )
     * )
     * @OA\GET(
     *     path="/active_substance/new",
     *     tags={"active_substance"},
     *     operationId="showCreateForm",
     *     description="Create form for adding new object",
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     * @Route("/new", name="active_substance_new", methods={"GET","POST"})
     */
    public function showCreateForm(Request $request):Response
    {
        $activeSubstance = new ActiveSubstance();
        $form = $this->createForm(ActiveSubstanceType::class, $activeSubstance);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $result = $this->create($activeSubstance);
            if($result == 202){

                return $this->redirectToRoute('active_substance_list', [], Response::HTTP_SEE_OTHER);
            }

            return new Response('Bad Request', 400);
        }

        return $this->render('new.html.twig', [
            'entityName' => 'Active substance',
            'path'   => 'active_substance_list',
            'form'   => $form->createView()
        ]);
    }
    public function create(ActiveSubstance $activeSubstance): int
    {
        if($activeSubstance->getSubstanceName()!== ' '){
            $this->serviceCRUD->add($activeSubstance);

            return 202;
        }
        
        return 400;        
    }

    /**
     * @OA\GET(
     *     path="/active_substance/{id}/",
     *     tags={"active_substance"},
     *     operationId="show",
     *     description="Find active substance by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of active substance that needed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             maximum=10,
     *             minimum=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     * @Route("/{id}/", name="active_substance_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        if($id!=0 && $this->serviceCRUD->readOne($id)){
            $activeSubstance = $this->serviceCRUD->readOne($id);

            return $this->render('active_substance/show.html.twig', [
                'entity' => $activeSubstance,
                'pathToDel' => 'active_substance_delete'
            ]);
        }

        return new Response('Not found',404);
    }

    /**
     * @OA\GET(
     *     path="/active_substance/{id}/edit",
     *     tags={"active_substance"},
     *     operationId="showEditForm",
     *     description="Create form for editing existing object",
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of active substance that needed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             maximum=10,
     *             minimum=1
     *         )
     *     )
     * )
     * @OA\Post(
     *     path="/active_substance/edit/{id}",
     *     tags={"active_substance"},
     *     operationId="edit",
     *     description="Save changes",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid name"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of active substance that needed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             maximum=10,
     *             minimum=1
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="body",
     *          in="path",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="activeSubstanceName", type="string")
     *          )
     *      )
     * )
     * @Route("/edit/{id}", name="active_substance_edit", methods={"GET","POST"})
     */
    public function showEditForm(Request $request, int $id):Response
    {
        if($id != 0 && $this->serviceCRUD->readOne($id)){
            $activeSubstance = $this->serviceCRUD->readOne($id);
            $form = $this->createForm(ActiveSubstanceType::class, $activeSubstance);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $result = $this->edit($activeSubstance);
                if($result == 202){

                    return $this->redirectToRoute('active_substance_list', [], Response::HTTP_SEE_OTHER);
                }

                return new Response('Bad Request', 400);
            }

            return $this->render('edit.html.twig', [
                'entityName' => 'Active substance',
                'path'       => 'active_substance_list',
                'form'       => $form->createView(),
                'entity'     => $activeSubstance,
                'pathToDel'  => 'active_substance_delete'
            ]);
        }
        
        return new Response('Not Found', 404);
    }
    public function edit(ActiveSubstance $activeSubstance): int
    {
        if($activeSubstance->getSubstanceName() != ' '){
            $this->serviceCRUD->update();
            
            return 202;
        }
        
        return 400;
    }

    /**
     * @OA\Post(
     *     path="/active_substance/{id}",
     *     tags={"active_substance"},
     *     operationId="delete",
     *     description="Create form for editing existing object",
     *     @OA\Response(
     *         response=200,
     *         description="Deleted"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id if active substance that needed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             maximum=10,
     *             minimum=1
     *         )
     *     )
     * )
     * @Route("delete/{id}", name="active_substance_delete", methods={"POST"})
     */
    public function delete(Request $request, ActiveSubstance $activeSubstance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activeSubstance->getId(), $request->request->get('_token'))) {
            $this->serviceCRUD->remove($activeSubstance);
        }

        return $this->redirectToRoute('active_substance_list', [], Response::HTTP_SEE_OTHER);
    }
}
