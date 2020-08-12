<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyFormType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/property")
 * Class AdminPropertyController
 * @package App\Controller\Admin
 */
class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $propertyRepo;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(PropertyRepository $propertyRepository, EntityManagerInterface $em)
    {
        $this->propertyRepo = $propertyRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="admin.property.index")
     * @return Response
     */
    public function index(): Response
    {
        $properties = $this->propertyRepo->findAll();

        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/new", name="admin.property.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $property = new Property();
        $propertyForm = $this->createForm(PropertyFormType::class, $property);
        $propertyForm->handleRequest($request);

        if($propertyForm->isSubmitted() &&  $propertyForm->isValid()) {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'L\'annonce a bien été ajoutée.');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'propertyForm' => $propertyForm->createView()
        ]);
    }


    /**
     * @Route("/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function edit(Property $property, Request $request): Response
    {
        $propertyForm = $this->createForm(PropertyFormType::class, $property);
        $propertyForm->handleRequest($request);

        if($propertyForm->isSubmitted() &&  $propertyForm->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'L\'annonce a bien été modifiée.');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'propertyForm' => $propertyForm->createView()
        ]);
    }


    /**
     * @Route("/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Suppression de l\'annonce effectué.');
        } else {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}