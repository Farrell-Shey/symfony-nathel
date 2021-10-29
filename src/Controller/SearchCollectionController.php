<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchCollectionController extends AbstractController
{
    /**
     * @Route("/search/collection", name="collection_search")
     */
    public function index(): Response
    {
        return $this->render('page/search-page.html.twig', [
            'controller_name' => 'SearchCollectionController',
        ]);
    }
}
