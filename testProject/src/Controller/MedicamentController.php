<?php
namespace App\Controller;

use App\Entity\Medicament;
use App\Entity\ActiveSubstance;
use App\Entity\Manufacturer;
use App\Service\CRUDService;
use App\Form\MedicamentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/medicament"
 * )
 */
/**
 * @Route("/medicament")
 */
class MedicamentController extends AbstractController
{
    protected CRUDService $serviceCRUD;

    public function __construct(ServiceController $serviceController)
    {
        $this->serviceCRUD = new CRUDService($serviceController, Medicament::class);
    }
    /**
     * @OA\Get(
     *     path="/medicament/",
     *     tags={"medicament"},
     *     operationId="list",
     *     description="Show all medicaments in array",
     *     @OA\Response(
     *         response=200,
     *         description="Rendered page"
     *     )
     * )
     * @Route("/", name="medicament_list", methods={"GET"})
     */
    public function list():Response
    {
        $medicamentsList = $this->serviceCRUD->readAll();
        
        return $this->render("medicament/list.html.twig",[
            "medicaments" => $medicamentsList,
        ]);
    }
    /**
     * @OA\Post(
     *     path="/medicament/create",
     *     tags={"medicament"},
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
     *             @OA\Property(property="medicamentName", type="string"),
     *             @OA\Property(property="substanceId", type="number"),
     *             @OA\Property(property="manufacturerId", type="number"),
     *             @OA\Property(property="price", type="number")
     *         )
     *     )
     * )
     * @OA\Get(
     *     path="/medicament/create",
     *     tags={"medicament"},
     *     operationId="showCreateForm",
     *     description="Create form for adding new object",
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     * @Route("/create", name="medicament_new", methods={"GET", "POST"})
     */
    public function showCreateForm(Request $request):Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->create($medicament);
            if($result == 201){

                return $this->redirectToRoute('medicament_list', [], 201);
            }elseif($result == 400){

                return new Response('Bad Request', 400);
            }
        }

        return $this->render("new.html.twig",[
            'entityName' => 'Medicament',
            'path'       => 'medicament_list',
            'form'       => $form->createView()
        ]);
    }

    public function create(Medicament $medicament):int
    {   
        if($medicament->getMedicamentName() != ' '){
            $substance = $this->serviceCRUD->readOne($medicament->getSubstanceId(),ActiveSubstance::class);
            $manufacturer = $this->serviceCRUD->readOne($medicament->getManufacturerId(), Manufacturer::class);
            if($substance && $manufacturer){
                
                $this->serviceCRUD->add($medicament);

                return 201;
            }
        }

        return 400;
    }
    /**
     * @OA\Get(
     *     path="/medicament/{id}/",
     *     tags={"medicament"},
     *     operationId="show",
     *     description="Find medicament by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of medicament that needed",
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
     * @Route("/{id}/", name="medicament_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        if($id != 0 && $this->serviceCRUD->readOne($id)){
            $medicament = $this->serviceCRUD->readOne($id);
            $substance = $this->serviceCRUD->readOne($medicament->getSubstanceId(), ActiveSubstance::class);
            $manufacturer = $this->serviceCRUD->readOne($medicament->getManufacturerId(), Manufacturer::class);
            
            return $this->render("medicament/show.html.twig",[
                'entity' => $medicament,
                'pathToDel'   => 'medicament_delete',
                'substanceName' => $substance->getSubstanceName(),
                'manufacturerName' => $manufacturer->getManufacturerName()
            ]);
        }
        
        return new Response('Not found', 404);
    }
    /**
     * @OA\Get(
     *     path="/medicament/edit/{id}",
     *     tags={"medicament"},
     *     operationId="showEditForm",
     *     description="Create form for editing existing object",
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of medicament that needed",
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
     *     path="/medicament/edit/{id}",
     *     tags={"medicament"},
     *     operationId="edit",
     *     description="Save changes",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid name"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of medicament that needed",
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
     *              @OA\Property(property="medicamentName", type="string"),
     *              @OA\Property(property="substanceId", type="number"),
     *              @OA\Property(property="manufacturerId", type="number"),
     *              @OA\Property(property="price", type="number")
     *          )
     *      )
     * )
     * @Route("/edit/{id}", name="medicament_edit", methods={"GET","POST"})
     */
    public function showEditForm(Request $request, int $id):Response
    {
        if($id != 0 && $this->serviceCRUD->readOne($id)){
            $medicament = $this->serviceCRUD->readOne($id);
            $form = $this->createForm(MedicamentType::class, $medicament);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $result = $this->edit($medicament);
                if($result == 200){

                    return $this->redirectToRoute('medicament_list', [], Response::HTTP_SEE_OTHER);
                }

                return new Response('Bad Request', 400);
            }

            return $this->render("edit.html.twig",[
                'entity'     => $medicament,
                'path'       => 'medicament_list',
                'entityName' => 'Medicament',
                'form'       => $form->createView(),
                'pathToDel'  => 'medicament_delete'
            ]);
        }

        return new Response('Not found', 404);
    }
    public function edit(Medicament $medicament):int
    {
        if($medicament->getMedicamentName() != ' '){
            $this->serviceCRUD->update();

            return 200;
        }
        
        return 400;
    }
    /**
     * @OA\Post(
     *     path="/medicament/delete/{id}",
     *     tags={"medicament"},
     *     operationId="delete",
     *     description="Create form for editing existing object",
     *     @OA\Response(
     *         response=200,
     *         description="Deleted"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id if medicament that needed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             maximum=10,
     *             minimum=1
     *         )
     *     )
     * )
     * @Route("/delete/{id}", name="medicament_delete", methods={"POST"})
     */
    public function delete(Request $request, Medicament $medicament)
    {
        if ($this->isCsrfTokenValid('delete'.$medicament->getId(), $request->request->get('_token'))) {
            $this->serviceCRUD->remove($medicament);
        }

        return $this->redirectToRoute('medicament_list', [], Response::HTTP_SEE_OTHER);
    }
}