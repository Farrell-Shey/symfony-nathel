<?php

namespace App\Controller;

use App\Entity\PoolSet;
use App\Form\PoolSetType;
use App\Repository\PoolSetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pool/set")
 */
class PoolSetController extends AbstractController
{
    /**
     * @Route("/", name="pool_set_index", methods={"GET"})
     */
    public function index(PoolSetRepository $poolSetRepository): Response
    {
        return $this->render('pool_set/index.html.twig', [
            'pool_sets' => $poolSetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pool_set_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $poolSet = new PoolSet();
        $form = $this->createForm(PoolSetType::class, $poolSet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poolSet);
            $entityManager->flush();

            return $this->redirectToRoute('pool_set_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pool_set/new.html.twig', [
            'pool_set' => $poolSet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pool_set_show", methods={"GET"})
     */
    public function show(PoolSet $poolSet): Response
    {
        return $this->render('pool_set/show.html.twig', [
            'pool_set' => $poolSet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pool_set_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PoolSet $poolSet): Response
    {
        $form = $this->createForm(PoolSetType::class, $poolSet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pool_set_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pool_set/edit.html.twig', [
            'pool_set' => $poolSet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pool_set_delete", methods={"POST"})
     */
    public function delete(Request $request, PoolSet $poolSet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poolSet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($poolSet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pool_set_index', [], Response::HTTP_SEE_OTHER);
    }
}
