<?php


namespace App\Controller;

use App\Entity\Option;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchFormType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $pRepository;

    private $em;

    /**
     * PropertyController constructor.
     * @param PropertyRepository $propertyRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(PropertyRepository $propertyRepository, EntityManagerInterface $em)
    {
        $this->pRepository = $propertyRepository;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        $options = $this->em->getRepository(Option::class)->find(1);

        // Créer une entité qui représente notre recherche
        $search = new PropertySearch();
        // Créer un formulaire de recherche
        $form = $this->createForm(PropertySearchFormType::class, $search);
        // Gérer le traitement
        $form->handleRequest($request);
        if ($form->isSubmitted() && !$form->isValid()) $search = new PropertySearch();

        $properties = $paginator->paginate(
            $this->pRepository->findAllFreeQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        // parameters to template
        return $this->render('property/index.html.twig', [
            'current_menu' =>  'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    public function show(Property $property, $slug): Response
    {
        if($property->getSlugTitle() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlugTitle(),
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'current_menu' => 'properties',
            'property' => $property
        ]);
    }

}