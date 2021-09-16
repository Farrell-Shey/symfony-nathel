<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListCollectionController extends AbstractController
{
    /**
     * @Route("/list", name="list_collection")
     */
    public function list(): Response
    {
        return $this->render('collection/list-collection.html.twig', [

        ]);
    }


    /**
     * @Route("/search", name="search")
     */
    public function search(): Response
    {
        return $this->render('collection/search-page.html.twig', [

        ]);
    }
}
