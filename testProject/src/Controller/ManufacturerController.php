<?php

namespace App\Controller;

use App\Entity\Manufacturer;
use App\Form\ManufacturerType;
use App\Service\CRUDService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @OA\Get(
 *     path="/manufacturer"
 * )
 */
/**
 * @Route("/manufacturer")
 */
class ManufacturerController extends AbstractController
{
    protected CRUDService $serviceCRUD;

    public function __construct(ServiceController $serviceController)
    {
        $this->serviceCRUD = new CRUDService($serviceController, Manufacturer::class);
    }
    /**
     * @OA\GET(
     *     path="/manufacturer/",
     *     tags={"manufacturer"},
     *     operationId="list",
     *     description="Show all manufacturers in array",
     *     @OA\Response(
     *         response=200,
     *         description="Rendered page"
     *     )
     * )
     * @Route("/", name="manufacturer_list", methods={"GET"})
     */
    public function list():Response
    {
        $manufacturer = $this->serviceCRUD->readAll();

        return $this->render('manufacturer/list.html.twig', [
            'manufacturers' => $manufacturer
        ]);
    }
    /**
     * @OA\Post(
     *     path="/manufacturer/create",
     *     tags={"manufacturer"},
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
     *             @OA\Property(property="manufacturerName", type="string"),
     *             @OA\Property(property="link", type="string")
     *         )
     *     )
     * )
     * @OA\GET(
     *     path="/manufacturer/create",
     *     tags={"manufacturer"},
     *     operationId="showCreateForm",
     *     description="Create form for adding new object",
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     * @Route("/create/", name="manufacturer_new", methods={"GET","POST"})
     */
    public function showCreateForm(Request $request):Response
    {
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $result = $this->create($manufacturer);
            if($result == 201){
                return $this->redirectToRoute('manufacturer_list', [], Response::HTTP_SEE_OTHER);
            }

            return new Response('Bad Request', 400);
        }

        return $this->render('new.html.twig', [
            'entityName' => 'Manufacturer',
            'path'   => 'manufacturer_list',
            'form'   => $form->createView()
        ]);
    }
    public function create(Manufacturer $manufacturer): int
    {
        if($manufacturer->getManufacturerName() != ' '){
            $this->serviceCRUD->add($manufacturer);

            return 201;
        }

        return 400;
    }
    /**
     * @OA\GET(
     *     path="/manufacturer/{id}/",
     *     tags={"manufacturer"},
     *     operationId="show",
     *     description="Find manufacturer by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of manufacturer that needed",
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
     * @Route("/{id}/", name="manufacturer_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        if($id!=0 && $this->serviceCRUD->readOne($id)){
            $manufacturer = $this->serviceCRUD->readOne($id);

            return $this->render("manufacturer/show.html.twig",[
                'entity' => $manufacturer,
                'pathToDel'   => 'manufacturer_delete'
            ]);
        }

        return new Response('Not found',404);
    }
    /**
     * @OA\GET(
     *     path="/manufacturer/edit/{id}",
     *     tags={"manufacturer"},
     *     operationId="showEditForm",
     *     description="Create form for editing existing object",
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of manufacturer that needed",
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
     *     path="/manufacturer/edit/{id}",
     *     tags={"manufacturer"},
     *     operationId="edit",
     *     description="Save changes",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid name"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of manufacturer that needed",
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
     *              @OA\Property(property="manufacturerName", type="string"),
     *              @OA\Property(property="link", type="string")
     *          )
     *      )
     * )
     * @Route("/edit/{id}", name="manufacturer_edit", methods={"GET","POST"})
     */
    public function showEditForm(Request $request, int $id):Response
    {
        if($id != 0 && $this->serviceCRUD->readOne($id)){
            $manufacturer = $this->serviceCRUD->readOne($id);
            $form = $this->createForm(ManufacturerType::class, $manufacturer);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $result = $this->edit($manufacturer);
                if($result == 200){
                    return $this->redirectToRoute('manufacturer_list', [], Response::HTTP_SEE_OTHER);
                }

                return new Response('Bad Request', 400);
            }

            return $this->render("edit.html.twig",[
                'entity'     => $manufacturer,
                'path'       => 'manufacturer_list',
                'entityName' => 'Manufacturer',
                'form'       => $form->createView(),
                'pathToDel'  => 'manufacturer_delete'
            ]);
        }

        return new Response('Not Found', 404);
    }
    public function edit(Manufacturer $manufacturer):int
    {
        if($manufacturer->getManufacturerName() != ' '){
            $this->serviceCRUD->update();
            
            return 200;
        }

        return 400;
    }
    /**
     * @OA\Post(
     *     path="/manufacturer/delete/{id}",
     *     tags={"manufacturer"},
     *     operationId="delete",
     *     description="Create form for editing existing object",
     *     @OA\Response(
     *         response=200,
     *         description="Deleted"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id if manufacturer that needed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             maximum=10,
     *             minimum=1
     *         )
     *     )
     * )
     * @Route("/delete/{id}", name="manufacturer_delete", methods={"POST"})
     */
    public function delete(Request $request, Manufacturer $manufacturer):RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$manufacturer->getId(), $request->request->get('_token'))) {
            $this->serviceCRUD->remove($manufacturer);
        }

        return $this->redirectToRoute('manufacturer_list', [], Response::HTTP_SEE_OTHER);
    }
}