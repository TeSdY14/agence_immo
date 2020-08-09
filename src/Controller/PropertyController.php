<?php


namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $pRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->pRepository = $propertyRepository;

    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(): Response
    {
        dump($this->pRepository);


        return $this->render('property/index.html.twig', [
            'current_menu' =>  'properties',
        ]);
    }

}