<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/collection", name="collection")
 */
class CollectionController extends AbstractController
{
    /**
     * @Route("/", name="_index")
     */
    public function index(): Response
    {
        return $this->render('collection/index.html.twig', [

        ]);
    }

    /**
     * @Route ("/modal", name="_modal")
     */
    public function modal(): Response
    {
        return $this->render('collection/_modal.html.twig', [

        ]);
    }
}