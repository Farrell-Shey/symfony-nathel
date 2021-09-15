<?php

namespace App\Controller;

use App\Entity\Mappool;
use App\Form\MappoolType;
use App\Repository\MappoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mappool")
 */
class MappoolController extends AbstractController
{


    /**
     * @Route("/", name="mappool_index", methods={"GET"})
     */
    public function index(MappoolRepository $mappoolRepository): Response
    {
        return $this->render('mappool/index.html.twig', [
            'mappools' => $mappoolRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mappool_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mappool = new Mappool();
        $form = $this->createForm(MappoolType::class, $mappool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mappool);
            $entityManager->flush();

            return $this->redirectToRoute('mappool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mappool/new.html.twig', [
            'mappool' => $mappool,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="mappool_show", methods={"GET"})
     */
    public function show(Mappool $mappool): Response
    {
        return $this->render('mappool/show.html.twig', [
            'mappool' => $mappool,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mappool_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mappool $mappool): Response
    {
        $form = $this->createForm(MappoolType::class, $mappool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mappool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mappool/edit.html.twig', [
            'mappool' => $mappool,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="mappool_delete", methods={"POST"})
     */
    public function delete(Request $request, Mappool $mappool): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mappool->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mappool);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mappool_index', [], Response::HTTP_SEE_OTHER);
    }
}
